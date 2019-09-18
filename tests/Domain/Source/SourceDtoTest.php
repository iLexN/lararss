<?php

declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Source\DTO\SourceData;
use Domain\Support\Enum\Status;
use Tests\TestCase;

final class SourceDtoTest extends TestCase
{

    public function testCreateFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $url = 'aaa';
        $status = Status::active();
        SourceData::createFromArray(
            [
                'url' => $url,
                'status' => $status // not true/false
            ]
        );
    }

    public function testToArray(): void
    {
        $url = 'http://www.example.com';
        $status = Status::active();
        $dto = SourceData::createFromArray(
            [
                'url' => $url,
                'status' => $status->getValue()
            ]
        );

        $expected = [
            'url' => $url,
            'status' => $status->getValue(),
        ];
        $this->assertEquals($expected, $dto->toArray());
    }

    public function testToArrayWithCallback(): void
    {
        $url = 'http://www.example.com';
        $status = Status::active();
        $dto = SourceData::createFromArray(
            [
                'url' => $url,
                'status' => $status->getValue()
            ]
        );

        $expected = [
            'url1' => $url,
            'status1' => $status,
        ];

        $callback = static function (SourceData $sourceData) {
            return [
                'url1' => $sourceData->getUrl(),
                'status1' => $sourceData->getStatus(),
            ];
        };

        $this->assertEquals($expected, $dto->toArray($callback));
    }
}
