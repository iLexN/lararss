<?php

declare(strict_types=1);

namespace Domain\Source\Repository;

use Domain\Source\DbModel\Source;
use Domain\Source\Model\SourceBusinessModel;
use Domain\Source\Model\SourceBusinessModelFactory;
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

    public function getOne(int $id): SourceBusinessModel
    {
        return $this->businessModelFactory->createOne($this->source::find($id));
    }

    /**
     * @return SourceBusinessModel[]|LazyCollection
     */
    public function getAll(): LazyCollection
    {
        return $this->source::cursor()
            ->map([$this->businessModelFactory, 'createOne']);
    }

    /**
     * @return SourceBusinessModel[]|LazyCollection
     */
    public function getActive(): LazyCollection
    {
        return $this->source::active()
            ->cursor()
            ->map([$this->businessModelFactory, 'createOne']);
    }
}
