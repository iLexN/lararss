<?php

declare(strict_types=1);

namespace Domain\GraphQlQuery;

use Domain\Post\Model\PostModel;
use Domain\Post\Repository\PostRepository;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class QueryPost
{
    /**
     * @var PostRepository
     */
    private $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * cos i want to pre load relationship,
     * so cursor will cause N+1,
     * need use get
     *
     * @Query()
     *
     * @param int $limit
     * @param int $offset
     *
     * @return PostModel[]|\Generator
     */
    public function getActivePost(int $limit, int $offset): \Generator
    {
        $postModels = $this->repository->findActiveWithRelation($limit, $offset);
        foreach ($postModels as $postModel) {
            yield $postModel;
        }
    }
}
