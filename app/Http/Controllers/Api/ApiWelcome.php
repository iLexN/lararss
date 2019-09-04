<?php

declare(strict_types=1);


namespace App\Http\Controllers\Api;


final class ApiWelcome
{

    public function __invoke()
    {
        return [
            'a' => 'is a',
            'api' => 'is api',
        ];
    }
}
