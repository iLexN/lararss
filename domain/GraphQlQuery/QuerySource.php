<?php

declare(strict_types=1);

namespace Domain\GraphQlQuery;

use Domain\Source\Domain\SourceBusinessModel;
use Domain\Source\Domain\SourceBusinessModelFactory;
use Domain\Source\Repository\SourceRepositoryInterface;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class QuerySource
{
    /**
     * @var SourceBusinessModelFactory
     */
    private $businessModelFactory;
    /**
     * @var SourceRepositoryInterface
     */
    private $repository;

    public function __construct(SourceBusinessModelFactory $businessModelFactory, SourceRepositoryInterface $repository)
    {
        $this->businessModelFactory = $businessModelFactory;
        $this->repository = $repository;
    }

    /**
     *
     * @Query
     * @param int $id
     * @return SourceBusinessModel|null
     */
    public function getSourceById(int $id): ?SourceBusinessModel
    {
        $source = $this->repository->getOne($id);
        if ($source === null) {
            return null;
        }
        return $this->businessModelFactory->createOne($source);
    }

    /**
     * @Query()
     *
     * @return SourceBusinessModel[]|\Generator
     */
    public function getSourceList(): \Generator
    {
        foreach ($this->repository->getAll() as $s) {
            yield $s;
        }
    }
}
