<?php
declare(strict_types=1);

use Domain\Post\DbModel\Post;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Post::class, static function (Faker $faker) {
    return [
        'title' => $faker->title,
        'url' => $faker->url,
        'description' => $faker->text,
        'created' => $faker->dateTime,
        'content'=> $faker->realText(),
        'status' => random_int(0,1),
        'pick' => random_int(0,1),
        'brand' => $faker->title,
    ];
});
