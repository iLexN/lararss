<?php

namespace App\Http\Middleware;

use Closure;

class DevTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        dump('start');

        $r = $next($request);

        dump(
            'end'
        );
        return $r;
    }
}
