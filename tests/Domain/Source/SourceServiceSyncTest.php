<?php

declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Post\Action\SyncPostAction;
use Domain\Post\Action\SyncPostFromFeedItemAction;
use Domain\Post\DbModel\Post;
use Domain\Post\DTO\NewPostDataFactory;
use Domain\Services\Rss\RssReaderInterface;
use Domain\Source\Action\LastSyncDateUpdateAction;
use Domain\Source\Action\SyncAllActiveSourceAction;
use Domain\Source\DbModel\Source;
use Domain\Source\Action\Error\SyncSourceUrlError;
use Domain\Source\Action\SyncOneSourceAction;
use Domain\Source\Model\SourceBusinessModel;
use Domain\Source\Repository\SourceRepository;
use Domain\Support\Enum\Status;
use Facade\IgnitionContracts\ProvidesSolution;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Domain\Source\Fake\FakeFeedItem;
use Tests\Domain\Source\Fake\FakeNullFeed;
use Tests\TestCase;
use Zend\Feed\Reader\Reader;

final class SourceServiceSyncTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Factory
     */
    private $validation;

    /**
     * @var RssReaderInterface|MockObject
     */
    private $reader;

    /**
     * @var SyncPostAction
     */
    private $action;
    /**
     * @var LastSyncDateUpdateAction
     */
    private $lastSync;
    /**
     * @var SyncOneSourceAction
     */
    private $testClass;

    /**
     * @var NewPostDataFactory
     */
    private $newPostDataFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reader = $this->createMock(RssReaderInterface::class);

        $this->testClass = new SyncOneSourceAction(
            $this->reader,
            $this->app->make(Factory::class),
            $this->app->make(LastSyncDateUpdateAction::class),
            $this->app->make(SyncPostFromFeedItemAction::class)
        );
    }

    public function testSyncUrlCatchError(): void
    {
        $source = factory(Source::class)->create([
            'url' => '',
        ]);
        $source = new SourceBusinessModel($source);

        try {
            $this->testClass->execute($source);
        } catch (SyncSourceUrlError $exception) {
            $this->assertInstanceOf(ProvidesSolution::class, $exception);
            $solution = $exception->getSolution();
            $this->assertEquals(SyncSourceUrlError::DESCRIPTION, $solution->getSolutionDescription());
        }
    }


    public function testSyncUrlIsEmptyWillThrow(): void
    {
        $this->expectException(SyncSourceUrlError::class);
        $source = factory(Source::class)->create([
            'url' => '',
        ]);

        $source = new SourceBusinessModel($source);
        $this->testClass->execute($source);
    }

    public function testSyncUrlIsInvalidWillThrow(): void
    {
        $this->expectException(SyncSourceUrlError::class);
        $source = factory(Source::class)->create([
            'url' => 'aa',
        ]);
        $source = new SourceBusinessModel($source);

        $this->testClass->execute($source);
    }


    public function testSyncUrlIsNotActiveUrlWillThrow(): void
    {
        $this->expectException(SyncSourceUrlError::class);
        $source = factory(Source::class)->create([
            'url' => 'http://www.abcddaa33deadas.com',
        ]);
        $source = new SourceBusinessModel($source);

        $this->testClass->execute($source);
    }

    public function testSyncUrlIsValid(): void
    {
        $source = factory(Source::class)->create([
            'url' => 'https://www.example.com',
        ]);
        $source = new SourceBusinessModel($source);

        $items = [
            new FakeFeedItem(),
            new FakeFeedItem(),
        ];
        $feed = new FakeNullFeed($items);

        $this->reader
            ->expects($this->once())
            ->method('import')
            ->willReturn($feed);

        $this->testClass->execute($source);

        $this->assertDatabaseHas('posts', [
            'title' => 'this is fake Tests\Domain\Source\Fake\FakeFeedItem::getTitle',
            'source_id' => $source->getId(),
        ]);
    }

    public function testSyncUrlToDb(): void
    {
        $source = factory(Source::class)->create([
            'url' => 'https://www.example.com',
        ]);
        $source = new SourceBusinessModel($source);

        $this->reader
            // run 2 time, 2nd time for check not create again
            ->expects($this->exactly(2))
            ->method('import')
            ->willReturnCallback(static function () {
                return Reader::importFile(__DIR__ . '/Fake/feed.xml');
            });

        $this->testClass->execute($source);

        $this->assertDatabaseHas('posts', [
            'source_id' => $source->getId(),
        ]);
        $count = Post::whereSourceId($source->getId())->count();
        $this->assertEquals(50, $count);

        //run same feel not create same feed item;
        $this->testClass->execute($source);
        $count = Post::whereSourceId($source->getId())->count();
        $this->assertEquals(50, $count);
    }

    public function testSyncAllActiveSource():void {
        $source = factory(Source::class)->create([
            'url' => 'https://www.example.com',
            'status' => Status::active()->getValue(),
        ]);
        $source = new SourceBusinessModel($source);

        factory(Source::class)->create([
            'url' => 'https://www.example.com',
            'status' => Status::inActive()->getValue(),
        ]);
        factory(Source::class)->create([
            'url' => 'https://www.example.com',
            'status' => Status::inActive()->getValue(),
        ]);

        $this->reader
            ->expects($this->once())
            ->method('import')
            ->willReturnCallback(static function () {
                return Reader::importFile(__DIR__ . '/Fake/feed.xml');
            });


        $syncAll = new SyncAllActiveSourceAction(
            $this->app->make(SourceRepository::class),
            $this->testClass
        );
        $syncAll->execute();

        $count = Post::whereSourceId($source->getId())->count();
        $this->assertEquals(50, $count);
    }
}
