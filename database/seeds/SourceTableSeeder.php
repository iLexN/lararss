<?php
declare(strict_types=1);

use Domain\Source\Model\Source;
use Illuminate\Database\Seeder;

class SourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        factory(Source::class)->create();
    }
}
