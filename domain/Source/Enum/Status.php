<?php

declare(strict_types=1);

namespace Domain\Source\Enum;

use MyCLabs\Enum\Enum;

final class Status extends Enum
{
    private const ACTIVE = true;
    private const INACTIVE = false;

    public static function active(): Status
    {
        return new self(self::ACTIVE);
    }

    public static function inActive(): Status
    {
        return new self(self::INACTIVE);
    }

    public function opeosite(): bool
    {
        return !$this->getValue();
    }
}