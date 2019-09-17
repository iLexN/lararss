<?php

declare(strict_types=1);

namespace Domain\Source\Domain\SubDomain;

use Domain\Source\Model\Source;
use Domain\Support\Enum\Status;

final class SourceIsActive
{
    public function __invoke(Source $source): bool
    {
        return $source->status === Status::active()->getValue();
    }
}