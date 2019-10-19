<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DevTime
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->isGraphPath($request)) {
            return $next($request);
        }

        return $this->run($request, $next);
    }

    private function isGraphPath(Request $request): bool
    {
        return $request->path() === 'graphql';
    }

    private function getPayloadKey(array $input): string
    {
        return \md5(\json_encode($input));
    }

    private function run(Request $request, Closure $next)
    {
        $payload = $request->input();
        $cacheKey = $this->getPayloadKey($payload);
        $cacheResponse = Cache::get($cacheKey, false);

        if ($cacheResponse !== false) {
            return $cacheResponse;
        }

        $r = $next($request);

        Cache::put($cacheKey, $r, 5);

        return $r;
    }
}
