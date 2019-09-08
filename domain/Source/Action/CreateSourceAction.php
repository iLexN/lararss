<?php

declare(strict_types=1);

namespace Domain\Source\Action;

use Domain\Source\DTO\SourceData;
use Domain\Source\Model\Source;

final class CreateSourceAction
{
    /**
     * @var Source
     */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function createFromArray(array $data): Source
    {
        return $this->source::create($data);
    }

    public function createFromDto(SourceData $sourceData): Source
    {
        return $this->source::create([
            'url' => $sourceData->getUrl(),
            'status' => $sourceData->getStatus()->getValue(),
        ]);
    }
}
