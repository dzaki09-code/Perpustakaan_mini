<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthService
{
    public function register($data)
    {
        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => User::ROLE_USER,
        ]);

        if ($user) {
            return true;
        }

        return false;
    }

    public function login($data)
    {
        try {
            if (Auth::attempt(['email' => $data['email'],'password' => $data['password']])) {
                if (! Auth::user()->isActive()) {
                    Auth::logout();
                    return false;
                }

                Session::put('logged_in', true);
                return true;
            };
            return false;
        } catch (\Exception $th) {
            Log::error('Auth service login failed', [
                'message'   => $th->getMessage(),
            ]);
            return false;
        }
    }
}

?>
