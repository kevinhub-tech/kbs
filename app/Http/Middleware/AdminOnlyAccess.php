<?php

namespace App\Http\Middleware;

use App\Helpers\SessionHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    private $role = 'admin';
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * for both web/api routes
         * Check if user is signed in and the role is admin
         * if web, redirect to previous. if api, return error json.
         */
        if ($request->hasHeader('Authorization') && str_contains($request->route()->getPrefix(), 'api')) {
            $error_message = "You are not authorized to access this api. Only signed in 'Admins' are allowed for access";
            if (SessionHandler::isTokenValid($request->header('Authorization'), $this->role)) {
                return $next($request);
            } else {
                return response()->json(
                    [
                        'status' => 'failure',
                        'message' => 'Failed to Authenticate',
                        'errors' => ['error' => $error_message],
                        'payload' => [],
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }
        } else {
            $error_message = "You are not authorized to visit this page. Only signed in 'Admins' are allowed!";
            if (SessionHandler::isUserAccessAllowed($this->role)) {
                return $next($request);
            } else {
                return redirect()->route('accessDeny')->with('accessDeny', $error_message);
            }
        }
    }
}
