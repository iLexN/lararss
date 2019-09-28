<?php

declare(strict_types=1);

namespace Domain\Source\Action;

use Domain\Source\Model\SourceOperationModel;
use Spatie\QueueableAction\QueueableAction;

final class UpdateSyncDateNowAction
{
    use QueueableAction;

    public function execute(SourceOperationModel $model): void
    {
        $model->setLastSync()->save();
    }
}
