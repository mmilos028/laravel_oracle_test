<?php
namespace App\Http\Middleware;

use Closure;
//use Illuminate\Contracts\Routing\Middleware;

class CacheKiller {

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(env('CACHE_KILLER', true)) {
            ini_set('opcache.revalidate_freq', '0');
            //delete cached views
            $cachedViewsDirectory = app('path.storage') . '/framework/views/';
            if ($handle = opendir($cachedViewsDirectory)) {
                while (false !== ($entry = readdir($handle))) {
                    if (strstr($entry, '.')) continue;
                    @unlink($cachedViewsDirectory . $entry);
                }
                closedir($handle);
            }

            //delete cache data
            $cachedViewsDirectory = app('path.storage') . '/framework/cache/data/';
            if ($handle = opendir($cachedViewsDirectory)) {
                while (false !== ($entry = readdir($handle))) {
                    if (strstr($entry, '.')) continue;
                    @unlink($cachedViewsDirectory . $entry);
                }
                closedir($handle);
            }
        }

        return $next($request);
    }

}