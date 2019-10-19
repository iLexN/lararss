<?php

declare(strict_types=1);

namespace Domain\Source\Action\Error;

use Domain\Source\Model\SourceBusinessModel;
use Exception;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

final class SyncSourceUrlError extends Exception implements ProvidesSolution
{
    public const DESCRIPTION = 'Please check the Source Url is not empty or is a valid url';

    /**
     * @return Solution
     */
    public function getSolution(): Solution
    {
        return BaseSolution::create($this->getMessage())
            ->setSolutionDescription(self::DESCRIPTION);
    }

    public static function createFromSourceModel(SourceBusinessModel $source, $error)
    {
        $message = sprintf('Source id(%d) with url(%s) have error: %s', $source->getId(), $source->getUrl(), $error);
        return new self($message);
    }
}
