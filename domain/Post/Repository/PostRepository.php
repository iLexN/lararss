<?php

declare(strict_types=1);

namespace Domain\Post\Repository;

use Domain\Post\DbModel\Post;
use Domain\Post\Model\PostModel;
use Domain\Post\Model\PostModelFactory;
use Domain\Source\Model\SourceBusinessModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\LazyCollection;

final class PostRepository
{
    /**
     * @var PostModelFactory
     */
    private $postModelFactory;

    public function __construct(PostModelFactory $postModelFactory)
    {
        $this->postModelFactory = $postModelFactory;
    }

    public function findOne(int $id): PostModel
    {
        return $this->postModelFactory->create(Post::findOrFail($id));
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return PostModel[]|LazyCollection
     */
    public function findActive(int $limit = 10, int $offset = 0): LazyCollection
    {
        return $this->listActive($limit, $offset)
            ->cursor()
            ->mapInto(PostModel::class);
    }

    private function listActive(int $limit, int $offset): Builder
    {
        return Post::active()
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit);
    }

    public function findActiveWithRelation(int $limit = 10, int $offset = 0)
    {
        return $this->listActive($limit, $offset)
            ->with('source')
            ->get()
            ->mapInto(PostModel::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return PostModel[]|LazyCollection
     */
    public function findActivePick(int $limit = 10, int $offset = 0): LazyCollection
    {
        return Post::active()
            ->pick()
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit)
            ->cursor()
            ->mapInto(PostModel::class);
    }

    /**
     * @param int $id
     * @param int $limit
     * @param int $offset
     * @return PostModel[]|LazyCollection
     */
    public function findBySourceId(int $id, int $limit = 10, int $offset = 0): LazyCollection
    {
        return Post::source($id)
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit)
            ->cursor()
            ->mapInto(PostModel::class);
    }

    /**
     * @param SourceBusinessModel $sourceBusinessModel
     * @param int $limit
     * @param int $offset
     * @return PostModel[]|LazyCollection
     */
    public function findBySourceModel(
        SourceBusinessModel $sourceBusinessModel,
        int $limit = 10,
        int $offset = 0
    ): LazyCollection {
        return $this->findBySourceId($sourceBusinessModel->getId(), $limit, $offset);
    }
}
