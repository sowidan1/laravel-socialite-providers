<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('auth.redirect');

Route::get('/auth/callback', function () {
    $github_user = Socialite::driver('github')->user();

    $user = User::updateOrCreate([
        'name' => $github_user->getName(),

    ], [
        'email' => $github_user->getEmail(),
        'provider_id' => $github_user->getId(),
    ]);

    Auth::login($user, true);

    return to_route('dashboard');
});

require __DIR__ . '/auth.php';
