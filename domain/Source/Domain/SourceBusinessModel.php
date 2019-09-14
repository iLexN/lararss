<?php

declare(strict_types=1);

namespace Domain\Source\Domain;

use Carbon\Carbon;
use Domain\Source\Model\Source;
use Domain\Support\Enum\Status;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="Source")
 *
 */
final class SourceBusinessModel
{
    public const UPDATED_RANGE_HOUR = 4;

    private $status;

    /**
     * @var Source
     */
    private $source;

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
     * @return \DateTimeInterface
     */
    public function getCreateAt(): \DateTimeInterface
    {
        return $this->source->created_at->toDateTimeImmutable();
    }

    /**
     * @Field()
     */
    public function shouldSync(): bool
    {
        if (!$this->isActive()) {
            return false;
        }
        return $this->isWithInUpdatedRange();
    }

    private function isWithInUpdatedRange(): bool
    {
        $now = Carbon::now();
        return $this->source->updated_at->diffInHours($now, false) > self::UPDATED_RANGE_HOUR;
    }

    public function isActive(): bool
    {
        return $this->source->status === Status::active()->getValue();
    }
}
