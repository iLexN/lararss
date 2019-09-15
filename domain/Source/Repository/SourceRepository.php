<?php

declare(strict_types=1);

namespace Domain\Source\Repository;

use Domain\Source\Domain\SourceBusinessModel;
use Domain\Source\Domain\SourceBusinessModelFactory;
use Domain\Source\Model\Source;
use Illuminate\Support\LazyCollection;

final class SourceRepository implements SourceRepositoryInterface
{
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

    public function getOne(int $id): ?Source
    {
        return $this->source::find($id);
    }

    public function getAll(): LazyCollection
    {
        return $this->source::cursor()->map([$this->businessModelFactory, 'createOne']);
    }

    /**
     * @return LazyCollection|SourceBusinessModel[]
     */
    public function getActive(): LazyCollection
    {
        return $this->source::active()->cursor()->map([$this->businessModelFactory, 'createOne']);
    }
}
