<?php

declare(strict_types=1);

namespace Domain\Source\Domain;

use Carbon\Carbon;
use Domain\Source\Model\Source;
use Domain\Support\Enum\Status;

final class SourceBusinessModel
{
    public const UPDATED_RANGE_HOUR = 4;

    /**
     * @var Source
     */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

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
