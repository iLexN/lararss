<?php

namespace App\Console\Commands;

use Domain\Source\Action\CreateSourceAction;
use Domain\Source\DTO\SourceData;
use Illuminate\Console\Command;

class CreateSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:create {url} {brand}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create source';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param \Domain\Source\Action\CreateSourceAction $createSourceAction
     *
     * @return mixed
     */
    public function handle(CreateSourceAction $createSourceAction): void
    {
        $array = [
            'status' => true,
            'url' => $this->argument('url'),
            'brand' => $this->argument('brand'),
        ];
        $sourceData = SourceData::createFromArray($array);
        $createSourceAction->execute($sourceData);
    }
}
