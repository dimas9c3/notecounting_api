<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->get('token');
        
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'result'    => 0,
                'message' => 'Token not provided.'
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'result'    => 0,
                'message' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'result'    => 0,
                'message'   => 'Invalid token'
            ], 400);
        }
        
        return $next($request);
    }
}
