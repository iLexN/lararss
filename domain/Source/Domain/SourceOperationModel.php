<?php

declare(strict_types=1);


namespace Domain\Source\Domain;

use Domain\Source\DbModel\Source;
use Domain\Support\Enum\Status;

final class SourceOperationModel
{
    /**
     * @var Source
     */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function save()
    {
        $this->source->save();
    }

    public function setActive(): SourceOperationModel
    {
        $this->source->status = Status::active()->getValue();
        return $this;
    }

    public function setInactive(): SourceOperationModel
    {
        $this->source->status = Status::inActive()->getValue();
        return $this;
    }
}
