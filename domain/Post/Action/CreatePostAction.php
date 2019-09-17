<?php

declare(strict_types=1);

namespace Domain\Post\Action;

use Domain\Post\DTO\PostData;
use Domain\Post\Model\Post;
use Spatie\QueueableAction\QueueableAction;

final class CreatePostAction
{
    use QueueableAction;

    public function __construct()
    {
    }

    public function execute(PostData $postData): Post
    {
        return Post::create($postData->toArray());
    }
}
