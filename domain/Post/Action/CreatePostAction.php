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
        return $postData->getSource()
            ->posts()
            ->create($postData->toArray(static function (PostData $data
            ): array {
                return [
                    'title' => $data->getTitle(),
                    'url' => $data->getUrl(),
                    'description' => $data->getDescription(),
                    'created' => $data->getCreated(),
                    'content' => $data->getContent(),
                ];
            }));

        //return $this->post::create($postData->toArray());
    }
}
