<?php
declare(strict_types=1);

use Domain\Source\DbModel\Source;
use Domain\Support\Enum\Brand;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Carbon;

/** @var Factory $factory */
$factory->define(Source::class, static function (Faker $faker) {
    return [
        'url' => $faker->url,
        'status' => 1,
        'last_sync' => Carbon::now(),
        'brand' => Brand::laravel()->getValue(),
    ];
});
