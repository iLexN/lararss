<?php

declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Post\Action\CreatePostAction;
use Domain\Services\Rss\RssReaderInterface;
use Domain\Source\Model\Source;
use Domain\Source\Services\Error\SyncSourceUrlError;
use Domain\Source\Services\SyncSource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Domain\Source\Fake\FakeFeedItem;
use Tests\Domain\Source\Fake\FakeNullFeed;
use Tests\TestCase;

final class SourceServiceSyncTest extends TestCase
{
    /**
     * @var Factory
     */
    private $validation;

    /**
     * @var RssReaderInterface|MockObject
     */
    private $reader;

    /**
     * @var CreatePostAction|MockObject
     */
    private $createAction;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->validation = $this->app->make(Factory::class);
        $this->reader = $this->createMock(RssReaderInterface::class);
        $this->createAction = $this->createMock(CreatePostAction::class);
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

        $this->createAction
            ->expects($this->never())
            ->method('execute');

        $s = new SyncSource($this->reader, $this->validation, $this->createAction);
        $s->sync($source);
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

        $this->createAction
            ->expects($this->never())
            ->method('execute');

        $s = new SyncSource($this->reader, $this->validation, $this->createAction);
        $s->sync($source);
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

        $this->createAction
            ->expects($this->never())
            ->method('execute');

        $s = new SyncSource($this->reader, $this->validation, $this->createAction);
        $s->sync($source);
    }

    /**
     * @throws SyncSourceUrlError
     */
    public function testSyncUrlIsValid(): void
    {
        $source = factory(Source::class)->make([
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

        $this->createAction
            ->expects($this->exactly(2))
            ->method('onQueue')
            ->willReturnSelf();
        $this->createAction
            ->expects($this->exactly(2))
            ->method('execute');

        $s = new SyncSource($this->reader, $this->validation, $this->createAction);
        $s->sync($source);
    }

}
