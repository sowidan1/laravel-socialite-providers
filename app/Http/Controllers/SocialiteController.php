<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function login($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $get_user = Socialite::driver($provider)->user();

        $user = User::updateOrCreate([
            'provider_id' => $get_user->getId(),
            'provider_name' => $provider,

        ], [
            'email' => $get_user->getEmail(),
            'name' => $get_user->getName(),
        ]);

        Auth::login($user, true);

        return to_route('dashboard');
    }
}
