<?php

declare(strict_types=1);

namespace Domain\Source\Action;

use Domain\Source\DbModel\Source;
use Domain\Source\DTO\SourceData;
use Domain\Source\Model\SourceBusinessModel;
use Domain\Source\Model\SourceBusinessModelFactory;
use Spatie\QueueableAction\QueueableAction;

final class CreateSourceAction
{
    use QueueableAction;

    /**
     * @var Source
     */
    private $source;
    /**
     * @var SourceBusinessModelFactory
     */
    private $businessModelFactory;

    public function __construct(Source $source, SourceBusinessModelFactory $businessModelFactory)
    {
        $this->source = $source;
        $this->businessModelFactory = $businessModelFactory;
    }

    public function execute(SourceData $sourceData): SourceBusinessModel
    {
        $source = $this->source::create($sourceData->toArray());
        return $this->businessModelFactory->createOne($source);
    }
}
