<?php

declare(strict_types=1);

namespace Domain\Source\Repository;

use Domain\Source\Action\CreateSourceAction;
use Domain\Source\DbModel\Source;
use Domain\Source\DTO\SourceData;
use Domain\Source\Model\SourceBusinessModel;
use Domain\Source\Model\SourceBusinessModelFactory;
use Illuminate\Support\Facades\Cache;
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
    /**
     * @var CreateSourceAction
     */
    private $createSourceAction;

    public function __construct(
        Source $source,
        SourceBusinessModelFactory $businessModelFactory,
        CreateSourceAction $createSourceAction
    ) {
        $this->source = $source;
        $this->businessModelFactory = $businessModelFactory;
        $this->createSourceAction = $createSourceAction;
    }

    public function getOne(int $id): SourceBusinessModel
    {
        return Cache::remember('source:'.$id, 5,  function () use ($id) {
            return $this->businessModelFactory->createOne($this->source::findOrFail($id));
        });
    }

    /**
     * @return SourceBusinessModel[]|LazyCollection
     */
    public function getAll(): LazyCollection
    {
//        return $this->source::cursor()
//            ->map([$this->businessModelFactory, 'createOne']);
        //cursor is lazy collection , so cannot cache
        return $this->source::cursor()
            ->mapInto(SourceBusinessModel::class);
    }

    /**
     * @return SourceBusinessModel[]|LazyCollection
     */
    public function getActive(): LazyCollection
    {
        return $this->source::active()
            ->cursor()
            //->map([$this->businessModelFactory, 'createOne']);
            ->mapInto(SourceBusinessModel::class);
    }

    public function createOne(SourceData $sourceData): SourceBusinessModel
    {
        return $this->createSourceAction->execute($sourceData);
    }
}
