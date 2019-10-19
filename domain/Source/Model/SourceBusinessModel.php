<?php

declare(strict_types=1);

namespace Domain\Source\Model;

use Carbon\CarbonImmutable;
use Domain\Source\DbModel\Source;
use Domain\Source\Model\Sub\SourceIsActive;
use Domain\Source\Model\Sub\SourceShouldSync;
use Domain\Support\Enum\Brand;
use Domain\Support\Enum\Status;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="Source")
 *
 */
final class SourceBusinessModel
{
    /**
     * @var Status
     */
    private $status;

    /**
     * @var Source
     */
    private $source;

    /**
     * @var Brand
     */
    private $brand;

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

    public function getBrand(): Brand
    {
        if ($this->brand === null) {
            $this->brand = new Brand($this->source->brand);
        }
        return $this->brand;
    }

    /**
     * @Field(name="brand")
     * @return string
     */
    public function getBrandValue(): string
    {
        return $this->getBrand()->getValue();
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

    public function toArray(callable $callback = null): array
    {
        if (is_callable($callback)) {
            return $callback($this);
        }
        return $this->source->toArray();
    }

    public function getOperationModel(): SourceOperationModel
    {
        return (new SourceOperationModelFactory())->createOne($this->source);
    }
}
