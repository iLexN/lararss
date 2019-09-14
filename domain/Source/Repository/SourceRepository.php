<?php

declare(strict_types=1);

namespace Domain\Source\Repository;

use Domain\Source\Model\Source;
use Illuminate\Support\LazyCollection;

final class SourceRepository implements SourceRepositoryInterface
{
    /**
     * @var Source
     */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function getOne(int $id): Source
    {
        return $this->source::find($id);
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
