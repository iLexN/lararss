<?php
declare(strict_types=1);

use Domain\Source\Model\Source;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Source::class, static function () {
    return [
        'url' => 'https://cms.scmp.com/rss/91/feed',
        'status' => 1,
    ];
});
