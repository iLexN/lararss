<?php

declare(strict_types=1);

namespace Domain\Post\Model;

use Carbon\CarbonImmutable;
use Domain\Post\DbModel\Post;
use Domain\Post\Enum\Pick;
use Domain\Source\Model\SourceBusinessModel;
use Domain\Source\Model\SourceBusinessModelFactory;
use Domain\Support\Enum\Status;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="Post")
 *
 */
final class PostModel
{
    /**
     * @var Post
     */
    private $post;

    /**
     * @var SourceBusinessModel
     */
    private $source;

    /**
     * @var Status
     */
    private $status;
    /**
     * @var Status
     */

    /**
     * @var Pick
     */
    private $pick;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @Field()
     * @return int
     */
    public function getId(): int
    {
        return $this->post->id;
    }

    /**
     * @Field()
     * @return string
     */
    public function getTitle(): string
    {
        return $this->post->title;
    }

    /**
     * @Field()
     * @return string
     */
    public function getUrl(): string
    {
        return $this->post->url;
    }

    /**
     * @Field()
     * @return string
     */
    public function getDescription(): string
    {
        return $this->post->description;
    }

    /**
     * @Field()
     * @return \DateTimeInterface|CarbonImmutable
     */
    public function getCreated(): \DateTimeInterface
    {
        return $this->post->created->toImmutable();
    }

    /**
     * @Field()
     * @return string
     */
    public function getContent(): string
    {
        return $this->post->content;
    }

    /**
     * @Field()
     * @return SourceBusinessModel
     */
    public function getSource(): SourceBusinessModel
    {
        if ($this->source === null) {
            $this->source = (new SourceBusinessModelFactory())->createOne($this->post->source);
        }
        return $this->source;
    }

    public function getStatus(): Status
    {
        if ($this->status === null) {
            $this->status = new Status($this->post->status);
        }
        return $this->status;
    }

    public function getPick(): Pick
    {
        if ($this->pick === null) {
            $this->pick = new Pick($this->post->pick);
        }
        return $this->pick;
    }
}
