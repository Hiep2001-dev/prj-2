<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;

use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginWithGoogleController extends Controller
{
    public function redirectToGoogle()

    {

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'phone_number' => '0000000000',
                    'email_verified_at'=>now(),
                    'role_id' => 3,
                    'password' => Hash::make('123456789'),
                ]);
            }

            $addressExists = Address::where('user_id', $user->id)->exists();
            if (!$addressExists) {
                Address::create([
                    'user_id' => $user->id,
                    'city' => 210,
                    'district' => 3217,
                    'ward' => 401203,
                    'apartment_number' => 'chua co dia chi',
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/');
        } catch (Exception $e) {

            dd($e->getMessage());
        }
    }
}