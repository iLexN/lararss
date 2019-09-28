<?php

declare(strict_types=1);

namespace Domain\Post\Action;

use Domain\Post\DbModel\Post;
use Domain\Post\DTO\NewPostData;
use Domain\Post\Repository\PostRepository;
use Spatie\QueueableAction\QueueableAction;

final class SyncPost
{
    use QueueableAction;
    /**
     * @var PostRepository
     */
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(NewPostData $postData): void
    {
        //get
        $post = $this->postRepository->findUrlByPostData($postData);

        if ($this->shouldUpdate($post, $postData)) {
            return;
        }
        $post->save();
    }

    private function shouldUpdate(Post $post, NewPostData $postData): bool
    {
        return $post->exists && $postData->isSame($post);
    }
}
