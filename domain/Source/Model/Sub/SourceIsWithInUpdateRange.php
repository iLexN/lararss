<?php

declare(strict_types=1);


namespace Domain\Source\Model\Sub;

use Carbon\Carbon;
use Domain\Source\DbModel\Source;

final class SourceIsWithInUpdateRange
{
    public const UPDATED_RANGE_HOUR = 4;

    public function __invoke(Source $source)
    {
        if ($source->last_sync === null){
            return true;
        }

        $now = Carbon::now();
        return $source->last_sync->diffInHours($now, false) > self::UPDATED_RANGE_HOUR;
    }
}
