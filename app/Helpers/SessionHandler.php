<?php

namespace App\Helpers;

use App\Models\roles;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;

class SessionHandler
{
    public static function storeSessionDetails($userId, $userName, $userRole, $userToken)
    {
        session([
            'userSignedIn' => true,
            'userName' => $userName,
            'userId' => $userId,
            'userRole' => $userRole,
            'userToken' => $userToken
        ]);
    }

    public static function removeSessionDetails()
    {
        Session::forget(
            'userSignedIn',
            'userName',
            'userId',
            'userRole',
            'userToken'
        );
    }

    public static function checkUserSession()
    {
        return Session::has([
            'userSignedIn',
            'userName',
            'userId',
            'userRole',
            'userToken'
        ]);
    }

    public static function isUserAccessAllowed(string $role)
    {
        if (self::checkUserSession() && session('userRole') === $role) {
            return true;
        }
        return false;
    }

    public static function isTokenValid(string $token, string $required_role)
    {
        if (!isset($token) || $token === null) {
            return false;
        }
        $user = users::where('token', $token)->first();
        if ($user) {
            $user_role = roles::find($user->role_id);
            if ($user_role->role_name === $required_role) {
                return true;
            }
        }
        return false;
    }
};
