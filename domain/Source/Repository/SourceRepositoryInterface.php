<?php

declare(strict_types=1);

namespace Domain\Source\Repository;

use Domain\Source\Model\Source;
use Illuminate\Support\LazyCollection;

interface SourceRepositoryInterface
{
    public function getOne(int $id): ?Source;

    public function getAll(): LazyCollection;

    public function getActive(): LazyCollection;
}
