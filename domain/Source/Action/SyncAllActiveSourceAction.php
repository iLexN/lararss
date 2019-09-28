<?php

declare(strict_types=1);


namespace Domain\Source\Action;

use Domain\Source\Model\SourceBusinessModel;
use Domain\Source\Repository\SourceRepository;
use Spatie\QueueableAction\QueueableAction;

final class SyncAllActiveSourceAction
{
    use QueueableAction;

    /**
     * @var SourceRepository
     */
    private $sourceRepository;
    /**
     * @var SyncOneSourceAction
     */
    private $action;

    public function __construct(
        SourceRepository $sourceRepository,
        SyncOneSourceAction $action
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->action = $action;
    }

    public function execute(): void
    {
        $sources = $this->sourceRepository->getActive();
        $sources->each(function (SourceBusinessModel $sourceBusinessModel) {
            $this->action->execute($sourceBusinessModel);
        });
    }
}
