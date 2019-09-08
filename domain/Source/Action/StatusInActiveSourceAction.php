<?php

declare(strict_types=1);

namespace Domain\Source\Action;

use Domain\Source\Domain\SourceOperationModel;
use Domain\Source\Domain\SourceOperationModelFactory;
use Domain\Source\Model\Source;

final class StatusInActiveSourceAction
{
    /**
     * @var SourceOperationModelFactory
     */
    private $factory;

    public function __construct(SourceOperationModelFactory $factory)
    {
        $this->factory = $factory;
    }

    public function byModel(Source $source): void
    {
        $domain = $this->factory->createOne($source);
        $this->byDomain($domain);
    }

    public function byDomain(SourceOperationModel $sourceDomain): void
    {
        $sourceDomain->setInactive()->save();
    }
}
