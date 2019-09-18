<?php

declare(strict_types=1);

namespace Domain\Source\Model;

use Carbon\CarbonImmutable;
use Domain\Source\DbModel\Source;
use Domain\Source\Model\Sub\SourceIsActive;
use Domain\Source\Model\Sub\SourceShouldSync;
use Domain\Support\Enum\Status;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="Source")
 *
 */
final class SourceBusinessModel
{
    private $status;

    /**
     * @var Source
     */
    public $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * @Field()
     * @return int
     */
    public function getId(): int
    {
        return $this->source->id;
    }

    /**
     * @Field()
     * @return string
     */
    public function getUrl(): string
    {
        return $this->source->url;
    }

    public function getStatus(): Status
    {
        if ($this->status === null) {
            $this->status = new Status($this->source->status);
        }
        return $this->status;
    }

    /**
     * @Field(name="status")
     * @return bool
     */
    public function getStatusValue(): bool
    {
        return $this->getStatus()->getValue();
    }

    /**
     * @Field()
     * @return \DateTimeInterface|CarbonImmutable
     */
    public function getCreateAt(): \DateTimeInterface
    {
        return $this->source->created_at->toImmutable();
    }

    /**
     * @Field()
     */
    public function shouldSync(): bool
    {
        return (new SourceShouldSync())($this->source);
    }

    public function isActive(): bool
    {
        return (new SourceIsActive())($this->source);
    }
}
