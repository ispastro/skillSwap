<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\VerificationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Email verification route
Route::prefix('email')->group(function () {
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->name('verification.verify');
});

/*
|--------------------------------------------------------------------------
| Social Auth Routes (optional)
|--------------------------------------------------------------------------
*/

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Logged-in users only)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    //  Log out
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    //  Get current logged-in user info
    Route::get('/me', [AuthController::class, 'me']);

    //  Resend email verification
    Route::post('/email/resend', [VerificationController::class, 'resend'])
        ->name('verification.send');

    /*
    |--------------------------------------------------------------------------
    | Profile Completed Routes (for users with complete profiles)
    |--------------------------------------------------------------------------
    */
    Route::middleware('profile.completed')->group(function () {

        //  Admin-only: get list of all users
        Route::get('/users', [UsersController::class, 'index'])->middleware('admin');

        //  User profile management
        Route::get('/users/{user}', [UsersController::class, 'show']);
        Route::put('/users/{user}', [UsersController::class, 'update']);
        Route::delete('/users/{user}', [UsersController::class, 'destroy'])->middleware('admin');

        //  Upload profile picture
        Route::post('/users/{user}/profile-picture', [UsersController::class, 'uploadProfilePicture']);
    });
});
