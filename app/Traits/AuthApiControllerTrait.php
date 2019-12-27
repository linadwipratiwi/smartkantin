<?php
namespace App\Traits;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

/**
 * AuthApiControllerTrait
 * Part Controller dari MobileApiController
 */
trait AuthApiControllerTrait
{
    /** Login by JWT */
    public function postLogin(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $response = [
            "success" => false,
            "message" => "user not found"
        ];

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $user = User::where('username', $username)->first();
            $token = JWTAuth::fromUser($user);

            $response = [
                "success" => true,
                "key" => "Bearer {$token}"
            ];
        }

        return response()
          ->json($response);
    }

    /** Test JWT Request */
    public function getUser()
    {
        $role = Auth::user()->roleUser;
        $response = [
            "success" => true,
            "data" => Auth::user(),
            "role" => $role
        ];
    
        return response()
            ->json($response);
    }
}
