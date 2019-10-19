<?php

declare(strict_types=1);

namespace Domain\Support\Enum;

use MyCLabs\Enum\Enum;

final class Brand extends Enum
{
    private const REDDIT = 'Reddit';

    private const STITCHER = 'Stitcher';

    private const SYMFONY = 'Symfony';

    private const LARAVEL = 'Laravel';

    public static function reddit(): Brand
    {
        return new self(self::REDDIT);
    }

    public static function stitcher(): Brand
    {
        return new self(self::STITCHER);
    }

    public static function symfony(): Brand
    {
        return new self(self::SYMFONY);
    }

    public static function laravel(): Brand
    {
        return new self(self::LARAVEL);
    }
}
