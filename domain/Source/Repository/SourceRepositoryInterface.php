<?php

declare(strict_types=1);

namespace Domain\Source\Repository;

use Illuminate\Support\LazyCollection;

interface SourceRepositoryInterface
{
    public function getOne(int $id);
    
    public function getAll(): LazyCollection;

    public function getActive(): LazyCollection;
}
