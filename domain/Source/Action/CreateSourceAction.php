<?php

declare(strict_types=1);

namespace Domain\Source\Action;

use Domain\Source\DTO\SourceData;
use Domain\Source\Model\Source;
use Spatie\QueueableAction\QueueableAction;

final class CreateSourceAction
{
    use QueueableAction;

    /**
     * @var Source
     */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function execute(SourceData $sourceData): Source
    {
        return $this->source::create([
            'url' => $sourceData->getUrl(),
            'status' => $sourceData->getStatus()->getValue(),
        ]);
    }
}
