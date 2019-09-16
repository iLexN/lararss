<?php

declare(strict_types=1);

namespace Domain\Post\Repository;

use Domain\Post\Model\Post;
use Domain\Source\Domain\SourceBusinessModel;
use Domain\Source\Model\Source;

final class PostRepository
{
    public function findOne(int $id): Post
    {
        return Post::find($id);
    }

    public function findActive(int $limit = 10, int $offset = 0): Post
    {
        return Post::active()
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function findBySourceId(int $id, int $limit = 10, int $offset = 0): Post
    {
        return Post::source($id)
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function findBySource(Source $source, int $limit = 10, int $offset = 0): Post
    {
        return $this->findBySourceId($source->id, $limit, $offset);
    }

    public function findBySourceModel(SourceBusinessModel $sourceBusinessModel, int $limit = 10, int $offset = 0): Post
    {
        return $this->findBySourceId($sourceBusinessModel->getId(), $limit, $offset);
    }
}
