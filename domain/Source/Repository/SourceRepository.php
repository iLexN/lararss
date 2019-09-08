<?php

declare(strict_types=1);

namespace Domain\Source\Repository;

use Domain\Source\Model\Source;
use Illuminate\Support\LazyCollection;

final class SourceRepository
{
    /**
     * @var Source
     */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function getAll(): LazyCollection
    {
        return $this->source::cursor();
    }

    public function getActive(): LazyCollection
    {
        return $this->source::active()->cursor();
    }
}