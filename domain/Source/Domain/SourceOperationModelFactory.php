<?php

declare(strict_types=1);

namespace Domain\Source\Domain;

use Domain\Source\Model\Source;

final class SourceOperationModelFactory
{
    public function createOne(Source $source): SourceOperationModel
    {
        return new SourceOperationModel($source);
    }
}