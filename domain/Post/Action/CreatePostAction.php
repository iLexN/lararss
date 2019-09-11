<?php

declare(strict_types=1);

namespace Domain\Post\Action;

use Domain\Post\DTO\PostData;
use Domain\Post\Model\Post;
use Spatie\QueueableAction\QueueableAction;

final class CreatePostAction
{
    use QueueableAction;

    /**
     * @var \Domain\Post\Model\Post
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function execute(PostData $postData): Post
    {
        return $this->post::create($postData->toArray());
    }
}