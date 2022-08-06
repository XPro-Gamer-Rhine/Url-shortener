<?php

namespace App\Listeners;

use Cache;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;

class RouterMatchedListener
{
    /**
     * Handle the event.
     *
     * @param  router.matched  $event
     * @return void
     */
    public function handle(Route $route)
    {
        $uri = md5($route->getUri());
        if( ! Cache::has($uri)) {
            Cache::forever($uri, 1);
        } else {
            dd(Cache::increment($uri));
        }
    }
}