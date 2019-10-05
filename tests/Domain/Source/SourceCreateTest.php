<?php
declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Source\Action\CreateSourceAction;
use Domain\Source\DTO\SourceData;
use Domain\Source\Model\SourceBusinessModelFactory;
use Domain\Support\Enum\Status;
use Domain\Source\DbModel\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CreateSourceAction
     */
    private $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateSourceAction(new Source(),new SourceBusinessModelFactory());
    }

    /**
     * @dataProvider getTestData
     *
     * @param string $url
     * @param bool $status
     */
    public function testCreateSourceFromArray(string $url, bool $status): void
    {

        $sourceData = SourceData::createFromArray([
            'url' => $url,
            'status' => $status,
        ]);

        $this->action->execute($sourceData);

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
        $this->action->execute($sourceData);

        $this->assertDatabaseHas('sources', [
            'url' => $sourceData->getUrl(),
            'status' => $sourceData->getStatus(),
        ]);
    }

    /**
     * @dataProvider getSourceData
     * @param SourceData $sourceData
     */
    public function testCreateSourceFromDtoWithQueue(SourceData $sourceData): void
    {
        $this->action->onQueue()->execute($sourceData);

        $this->assertDatabaseHas('sources', [
            'url' => $sourceData->getUrl(),
            'status' => $sourceData->getStatus(),
        ]);
    }

    public function getTestData(): \Generator
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
     * @return \Generator
     */
    public function getSourceData(): \Generator
    {

        yield 'url with active' => [
            SourceData::createFromArray([
                'url' => 'https://www.example.com',
                'status' => Status::active()->getValue(),
            ])
        ];

        yield 'url with in-active' => [
            SourceData::createFromArray([
                'url' => 'https://www.example1.com',
                'status' => Status::inActive()->getValue(),
            ])
        ];
    }
}
