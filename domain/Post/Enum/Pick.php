<?php

declare(strict_types=1);

namespace Domain\Post\Enum;

use MyCLabs\Enum\Enum;

final class Pick extends Enum
{
    private const PICK = true;
    private const UN_PICK = false;

    public static function pick(): Pick
    {
        return new self(self::PICK);
    }

    public static function unpick(): Pick
    {
        return new self(self::UN_PICK);
    }

    public function opposite(): bool
    {
        return !$this->getValue();
    }
}
