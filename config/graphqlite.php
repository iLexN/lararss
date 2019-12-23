<?php

use App\Http\Middleware\DevTime;
use GraphQL\Error\Debug;

return [
    /*
     |--------------------------------------------------------------------------
     | GraphQLite Configuration
     |--------------------------------------------------------------------------
     |
     | Use this configuration to customize the namespace of the controllers and
     | types.
     | These namespaces must be autoloadable from Composer.
     | GraphQLite will find the path of the files based on composer.json settings.
     |
     | You can put a single namespace, or an array of namespaces.
     |
     */
    //'controllers' => 'App\\Http\\Controllers',
    'controllers' => 'Domain\\GraphQlQuery',
    'types' => 'Domain\\',
    'debug' => Debug::RETHROW_UNSAFE_EXCEPTIONS,
    'uri' => '/graphql',
    //'middleware' =>  ['api'],
    //'middleware' =>  [DevTime::class],
    'middleware' =>  [],
];
