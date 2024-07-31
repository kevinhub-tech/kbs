<?php

namespace App\Http\Middleware;

use App\Helpers\SessionHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserOnlyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private $role = 'user';

    public function handle(Request $request, Closure $next): Response
    {
        /**
         * for both web/api routes
         * Check if user is signed in and the role is user
         * if web, redirect to previous. if api, return error json.
         */

        if ($request->hasHeader('Authorization') && $request->isJson()) {
            $error_message = "You are not authorized to access this api. Only users are allowed for access";
            if (SessionHandler::isTokenValid($request->header('Authorization'), self::$role)) {
                return $next($request);
            } else {
                return response()->json([
                    [
                        'status' => 'failure',
                        'message' => 'Failed to Authenticate',
                        'errors' => ['error' => $error_message],
                        'payload' => [],
                    ],
                    Response::HTTP_UNAUTHORIZED
                ]);
            }
        } else {
            $error_message = "You are not authorized to visit this page. Only Users are allowed";
            if (SessionHandler::isUserAccessAllowed(self::$role)) {
                return $next($request);
            } else {
                redirect()->back()->with('accessDeny', $error_message);
            }
        }
    }
}
