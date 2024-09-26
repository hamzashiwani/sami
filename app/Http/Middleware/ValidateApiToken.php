<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
// use Illuminate\Support\Facades\Response;

class ValidateApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'data'       => json_decode('{}'),
                    'statusCode' => 401,
                    'message'    => 'Token is Invalid'
                ]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    'data'       => json_decode('{}'),
                    'statusCode' => 401,
                    'message'    => 'Token is Expired'
                ]);
            }else{
                return response()->json([
                    'data'       => json_decode('{}'),
                    'statusCode' => 401,
                    'message'    => 'Authorization Token not found'
                ]);
            }
        }

        return $next($request);
    }
}
