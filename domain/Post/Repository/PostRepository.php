<?php

declare(strict_types=1);

namespace Domain\Post\Repository;

use Domain\Post\DbModel\Post;
use Domain\Source\DbModel\Source;
use Domain\Source\Domain\SourceBusinessModel;
use Illuminate\Support\LazyCollection;

final class PostRepository
{
    public function findOne(int $id): Post
    {
        return Post::find($id);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Post[]|LazyCollection
     */
    public function findActive(int $limit = 10, int $offset = 0): LazyCollection
    {
        return Post::active()
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit)
            ->cursor();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Post[]|LazyCollection
     */
    public function findActivePick(int $limit = 10, int $offset = 0): LazyCollection
    {
        return Post::active()
            ->pick()
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit)
            ->cursor();
    }

    /**
     * @param int $id
     * @param int $limit
     * @param int $offset
     * @return Post[]|LazyCollection
     */
    public function findBySourceId(int $id, int $limit = 10, int $offset = 0): LazyCollection
    {
        return Post::source($id)
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit)
            ->cursor();
    }

    /**
     * @param Source $source
     * @param int $limit
     * @param int $offset
     * @return Post[]|LazyCollection
     */
    public function findBySource(Source $source, int $limit = 10, int $offset = 0): LazyCollection
    {
        return $this->findBySourceId($source->id, $limit, $offset);
    }

    /**
     * @param SourceBusinessModel $sourceBusinessModel
     * @param int $limit
     * @param int $offset
     * @return Post[]|LazyCollection
     */
    public function findBySourceModel(
        SourceBusinessModel $sourceBusinessModel,
        int $limit = 10,
        int $offset = 0
    ): LazyCollection {
        return $this->findBySourceId($sourceBusinessModel->getId(), $limit, $offset);
    }
}
