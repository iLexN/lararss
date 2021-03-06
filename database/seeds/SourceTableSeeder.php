<?php
declare(strict_types=1);

use Domain\Post\DbModel\Post;
use Domain\Source\DbModel\Source;
use Illuminate\Database\Seeder;

class SourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run():void
    {
        factory(Source::class, 20)->create()->each(static function (Source $source) {
            $source->posts()->saveMany(factory(Post::class, 50)->make());
        });
    }
}
