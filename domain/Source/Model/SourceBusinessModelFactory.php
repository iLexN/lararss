<?php

declare(strict_types=1);

namespace Domain\Source\Model;

use Domain\Source\DbModel\Source;

final class SourceBusinessModelFactory
{
    public function createOne(Source $source): SourceBusinessModel
    {
        return new SourceBusinessModel($source);
    }
}
