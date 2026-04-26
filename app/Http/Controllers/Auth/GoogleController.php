<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirect to Google login page
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google sends the user back here after login
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Find existing user or create a new one
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'     => $googleUser->getName(),
                'password' => bcrypt(\Illuminate\Support\Str::random(24)),
            ]
        );

        Auth::login($user);

        return redirect()->route('tasks.index');
    }
}
