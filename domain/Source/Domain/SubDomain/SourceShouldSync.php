<?php

declare(strict_types=1);

namespace Domain\Source\Domain\SubDomain;

use Domain\Source\DbModel\Source;

final class SourceShouldSync
{
    public function __invoke(Source $source): bool
    {
        if (!(new SourceIsActive())($source)) {
            return false;
        }
        return (new SourceIsWithInUpdateRange())($source);
    }
}
