<?php

declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Source\DTO\SourceData;
use Domain\Support\Enum\Status;
use Tests\TestCase;

final class SourceDtoTest extends TestCase
{

    public function testToArray(): void
    {
        $url = 'aaa';
        $status = Status::active();
        $dto = new SourceData(
            $url,
            $status
        );

        $expected = [
            'url' => $url,
            'status' => $status->getValue(),
        ];
        $this->assertEquals($expected, $dto->toArray());
    }

    public function testToArrayWithCallback(): void
    {
        $url = 'aaa';
        $status = Status::active();
        $dto = new SourceData(
            $url,
            $status
        );

        $expected = [
            'url1' => $url,
            'status1' => $status,
        ];

        $callback = static function(SourceData $sourceData) use ($expected){
            return $expected;
        };

        $this->assertEquals($expected, $dto->toArray($callback));
    }
}
