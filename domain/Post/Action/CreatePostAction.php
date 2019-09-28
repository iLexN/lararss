<?php

declare(strict_types=1);

namespace Domain\Post\Action;

use Domain\Post\DbModel\Post;
use Domain\Post\DTO\NewPostData;
use Spatie\QueueableAction\QueueableAction;

final class CreatePostAction
{
    use QueueableAction;

    public function __construct()
    {
    }

    public function execute(NewPostData $postData): Post
    {
        return Post::create($postData->toArray());
    }
}
