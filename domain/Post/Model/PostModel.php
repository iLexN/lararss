<?php
declare(strict_types=1);

namespace Domain\Post\Model;

use Carbon\CarbonImmutable;
use Domain\Post\DbModel\Post;
use Domain\Post\Enum\Pick;
use Domain\Source\Model\SourceBusinessModel;
use Domain\Source\Model\SourceBusinessModelFactory;
use Domain\Support\Enum\Status;

final class PostModel
{
    /**
     * @var Post
     */
    private $post;
    /**
     * @var SourceBusinessModelFactory
     */
    private $sourceBusinessModel;

    public function __construct(Post $post,SourceBusinessModelFactory $sourceBusinessModel)
    {
        $this->post = $post;
        $this->sourceBusinessModel = $sourceBusinessModel;
    }

    public function getId(): int
    {
        return $this->post->id;
    }

    public function getTitle(): string
    {
        return $this->post->title;
    }

    public function getUrl(): string
    {
        return $this->post->url;
    }

    public function getDescription(): string
    {
        return $this->post->description;
    }

    public function getCreated(): CarbonImmutable
    {
        return $this->post->created->toImmutable();
    }

    public function getContent(): string
    {
        return $this->post->content;
    }

    public function getSource(): SourceBusinessModel
    {
        return $this->sourceBusinessModel->createOne($this->post->source);
    }

    public function getStatus(): Status
    {
        return new Status($this->post->status);
    }

    public function getPick(): Pick
    {
        return new Pick($this->post->pick);
    }
}
