<?php

declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Post\Action\SyncPost;
use Domain\Post\DbModel\Post;
use Domain\Services\Rss\RssReaderInterface;
use Domain\Source\DbModel\Source;
use Domain\Source\Action\Error\SyncSourceUrlError;
use Domain\Source\Action\SyncSourceAction;
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
     * @var SyncPost
     */
    private $action;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->validation = $this->app->make(Factory::class);
        $this->reader = $this->createMock(RssReaderInterface::class);
        $this->action = $this->app->make(SyncPost::class);
    }

    public function testSyncUrlCatchError():void {
        $source = factory(Source::class)->create([
            'url' => '',
        ]);
//        $this->createAction
//            ->expects($this->never())
//            ->method('execute');

        $s = new SyncSourceAction($this->reader, $this->validation, $this->action);
        try{
            $s->execute($source);
        } catch (SyncSourceUrlError $exception){
            $this->assertInstanceOf(ProvidesSolution::class, $exception);
            $solution = $exception->getSolution();
            $this->assertEquals(SyncSourceUrlError::DESCRIPTION, $solution->getSolutionDescription());
        }
    }

    /**
     * @throws SyncSourceUrlError
     */
    public function testSyncUrlIsEmptyWillThrow(): void
    {
        $this->expectException(SyncSourceUrlError::class);
        $source = factory(Source::class)->make([
            'url' => '',
        ]);

//        $this->createAction
//            ->expects($this->never())
//            ->method('execute');

        $s = new SyncSourceAction($this->reader, $this->validation, $this->action);
        $s->execute($source);
    }

    /**
     * @throws SyncSourceUrlError
     */
    public function testSyncUrlIsInvalidWillThrow(): void
    {
        $this->expectException(SyncSourceUrlError::class);
        $source = factory(Source::class)->make([
            'url' => 'aa',
        ]);

//        $this->createAction
//            ->expects($this->never())
//            ->method('execute');

        $s = new SyncSourceAction($this->reader, $this->validation, $this->action);
        $s->execute($source);
    }

    /**
     * @throws SyncSourceUrlError
     */
    public function testSyncUrlIsNotActiveUrlWillThrow(): void
    {
        $this->expectException(SyncSourceUrlError::class);
        $source = factory(Source::class)->make([
            'url' => 'http://www.abcddaa33deadas.com',
        ]);

//        $this->createAction
//            ->expects($this->never())
//            ->method('execute');

        $s = new SyncSourceAction($this->reader, $this->validation, $this->action);
        $s->execute($source);
    }

    /**
     * @throws SyncSourceUrlError
     */
    public function testSyncUrlIsValid(): void
    {
        $source = factory(Source::class)->create([
            'url' => 'https://www.example.com',
        ]);

        $items = [
            new FakeFeedItem(),
            new FakeFeedItem(),
        ];
        $feed = new FakeNullFeed($items);

        $this->reader
            ->expects($this->once())
            ->method('import')
            ->willReturn($feed);

        $s = new SyncSourceAction($this->reader, $this->validation, $this->action);
        $s->execute($source);

        $this->assertDatabaseHas('posts', [
            'title' => 'this is fake Tests\Domain\Source\Fake\FakeFeedItem::getTitle',
            'source_id' => $source->id,
        ]);
    }

    /**
     * @throws SyncSourceUrlError
     * @throws BindingResolutionException
     */
    public function testSyncUrlToDb(): void
    {
        $source = factory(Source::class)->create([
            'url' => 'https://www.example.com',
        ]);

        $this->reader
            // run 2 time, 2nd time for check not create again
            ->expects($this->exactly(2))
            ->method('import')
            ->willReturnCallback(static function () {
                return Reader::importFile(__DIR__ . '/Fake/feed.xml');
            });

        $s = new SyncSourceAction($this->reader, $this->validation, $this->action);
        $s->execute($source);

        $this->assertDatabaseHas('posts', [
            'source_id' => $source->id,
        ]);
        $count = Post::whereSourceId($source->id)->count();
        $this->assertEquals(50, $count);

        //run same feel not create same feed item;
        $s->execute($source);
        $count = Post::whereSourceId($source->id)->count();
        $this->assertEquals(50, $count);
    }
}
