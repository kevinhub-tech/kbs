<?php

namespace App\Helpers;

use App\Models\roles;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;

class SessionHandler
{
    public static function storeSessionDetails($userId, $userRole, $userToken)
    {
        session([
            'userSignedIn' => true,
            'userId' => $userId,
            'userRole' => $userRole,
            'userToken' => $userToken
        ]);
    }

    public static function removeSessionDetails()
    {
        Session::forget(
            'userSignedIn',
            'userId',
            'userRole',
            'userToken'
        );
    }

    public static function checkUserSession()
    {
        return Session::has([
            'userSignedIn',
            'userId',
            'userRole',
            'userToken'
        ]);
    }

    public static function isUserAccessAllowed(string $role)
    {
        $user_role = roles::find(session('userRole'));

        if (self::checkUserSession() && $user_role->role_name === $role) {
            return true;
        }
        return false;
    }

    public static function isTokenValid(string $token, string $required_role)
    {
        if (isset($token) || $token === null) {
            return false;
        }
        $user = users::where('token', $token);

        if ($user) {
            $user_role = roles::find($user->role_id);
            if ($user_role->role_name === $required_role) {
                return true;
            }
        }
        return false;
    }
};
