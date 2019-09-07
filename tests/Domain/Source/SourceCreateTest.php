<?php
declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Source\Action\CreateSourceAction;
use Domain\Source\DTO\SourceData;
use Domain\Source\Enum\Status;
use Domain\Source\Model\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider getTestData
     *
     * @param string $url
     * @param int $status
     */
    public function testCreateSourceFromArray(string $url, int $status): void
    {
        $source = new CreateSourceAction(new Source());
        $source->createFromArray([
            'url' => $url,
            'status' => $status,
        ]);

        $this->assertDatabaseHas('sources', [
            'url' => $url,
            'status' => $status,
        ]);
    }

    /**
     * @dataProvider getSourceData
     * @param SourceData $sourceData
     */
    public function testCreateSourceFromDto(SourceData $sourceData): void
    {
        $source = new CreateSourceAction(new Source());
        $source->createFromDto($sourceData);

        $this->assertDatabaseHas('sources', [
            'url' => $sourceData->getUrl(),
            'status' => $sourceData->getStatus(),
        ]);
    }

    public function getTestData(): ?\Generator
    {
        yield 'url with active' => [
            'https://www.example.com',
            Status::active()->getValue(),
        ];

        yield 'url with in-active' => [
            'https://www.example1.com',
            Status::inActive()->getValue(),
        ];
    }

    /**
     * @param string $url
     * @param int $status
     * @return \Generator|null
     */
    public function getSourceData(): ?\Generator
    {

        yield 'url with active' => [
            new SourceData('https://www.example.com', Status::active())
        ];

        yield 'url with in-active' => [
            new SourceData('https://www.example1.com', Status::inActive())
        ];
    }
}
