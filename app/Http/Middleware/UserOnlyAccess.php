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

        if ($request->hasHeader('Authorization') || str_contains($request->route()->getPrefix(), 'api')) {
            $error_message = "You are not authorized to access this api. Only signed in 'Users' are allowed for access";
            if (!$request->hasHeader('Authorization')) {
                return response()->json(
                    [
                        'status' => 'failure',
                        'message' => 'Failed to Authenticate',
                        'errors' => ['error' => 'Authorization token has not been passed.'],
                        'payload' => [],
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            } else {
                if (SessionHandler::isTokenValid($request->header('Authorization'), $this->role)) {
                    return $next($request);
                } else {
                    return response()->json(
                        [
                            'status' => 'failure',
                            'message' => 'Failed to Authenticate',
                            'errors' => ['error' => $error_message],
                            'payload' => [$request->header('Authorization'), $this->role, SessionHandler::isTokenValid($request->header('Authorization'), $this->role)],
                        ],
                        Response::HTTP_UNAUTHORIZED
                    );
                }
            }
        } else {
            $error_message = "You are not authorized to visit this page. Only signed in 'Users' are allowed!";
            if (SessionHandler::isUserAccessAllowed($this->role)) {
                return $next($request);
            } else {
                return redirect()->route('accessDeny')->with('accessDeny', $error_message)->with('roleAccess', 'user');
            }
        }
    }
}
