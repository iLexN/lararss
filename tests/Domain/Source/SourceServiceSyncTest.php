<?php

declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Services\Rss\RssReaderInterface;
use Domain\Source\Model\Source;
use Domain\Source\Services\Error\SyncSourceUrlError;
use Domain\Source\Services\SyncSource;
use Illuminate\Contracts\Validation\Factory;
use Tests\TestCase;

final class SourceServiceSyncTest extends TestCase
{
    /**
     * @var Factory
     */
    private $validation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validation = $this->app->make(Factory::class);
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

        $reader = $this->createMock(RssReaderInterface::class);

        $s = new SyncSource($reader, $this->validation);
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

        $reader = $this->createMock(RssReaderInterface::class);

        $s = new SyncSource($reader, $this->validation);
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

        $reader = $this->createMock(RssReaderInterface::class);

        $s = new SyncSource($reader, $this->validation);
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

        $reader = $this->createMock(RssReaderInterface::class);
        $reader->expects($this->once())->method('import');

        $s = new SyncSource($reader, $this->validation);
        $s->sync($source);
    }

}
