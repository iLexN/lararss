<?php

declare(strict_types=1);

namespace Domain\Post\Model;

use Domain\Post\DbModel\Post;

final class PostModelFactory
{
    public function create(Post $post): PostModel
    {
        return new PostModel($post);
    }
}
