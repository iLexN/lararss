<?php

declare(strict_types=1);

namespace Domain\Source\Repository;

use Domain\Source\Domain\SourceBusinessModel;
use Illuminate\Support\LazyCollection;

interface SourceRepositoryInterface
{
    public function getOne(int $id): SourceBusinessModel;

    public function getAll(): LazyCollection;

    public function getActive(): LazyCollection;
}
