<?php

namespace App\Console\Commands;

use Domain\Source\Action\SyncAllActiveSourceAction;
use Illuminate\Console\Command;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all active source';

    /**
     * @var SyncAllActiveSourceAction
     */
    private $action;

    /**
     * Create a new command instance.
     *
     * @param SyncAllActiveSourceAction $action
     */
    public function __construct(SyncAllActiveSourceAction $action)
    {
        parent::__construct();
        $this->action = $action;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $this->action->execute();
    }
}
