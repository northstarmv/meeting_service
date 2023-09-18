<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$params)
    {
        // Pre-Middleware Action
        $authorizationData = json_decode(file_get_contents(resource_path('/Json/authorization.json')), true);
        if (!in_array($request["auth"]["role"], $authorizationData[$params[0]][$params[1]])) {
            
            return response()->json([
                'status'=>'error',
                'message'=>'Unauthorized',
            ],401);
        }

        
      
        return $next($request);
    }
}
