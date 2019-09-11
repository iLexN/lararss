<?php
declare(strict_types=1);

use Domain\Source\Model\Source;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Source::class, static function (Faker $faker) {
    return [
        'url' => $faker->url,
        'status' => 1,
    ];
});
