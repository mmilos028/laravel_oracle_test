<?php namespace App\Http\Middleware;

use Closure;
use App\Helpers\JWTHelper;

class Cors {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if($request->hasHeader('JWT'))
        {
            $result = JWTHelper::validateToken($request->header('JWT'));
            if($result['status'] != 'OK')
            {
                return response()->json([
                    "status" => "NOK",
                    "message" => 'Invalid JWT token',
                ]);
            }
        }
        
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            //->header('Access-Control-Max-Age', 3600)            
            ->header('Access-Control-Allow-Methods', 'GET, POST')
            ->header('Access-Control-Allow-Headers', '*')
        ;
    }

}