<?php

declare(strict_types=1);

namespace Domain\Source\Model;

use Domain\Source\DbModel\Source;

final class SourceOperationModelFactory
{
    public function createOne(Source $source): SourceOperationModel
    {
        return new SourceOperationModel($source);
    }
}
