<?php

declare(strict_types=1);


namespace Domain\Source\Domain;

use Domain\Source\Model\Source;
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

    public function setActive()
    {
        $this->source->status = Status::active()->getValue();
        return $this;
    }

    public function setInactive()
    {
        $this->source->status = Status::inActive()->getValue();
        return $this;
    }
}
