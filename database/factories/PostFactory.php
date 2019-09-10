<?php
declare(strict_types=1);


use Domain\Post\Model\Post;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Post::class, static function (Faker $faker) {
    return [
        'title' => 'title',
        'url' => 'this is url',
        'description' => 'this is description',
        'created' => now(),
        'content'=> 'long long content',
    ];
});
