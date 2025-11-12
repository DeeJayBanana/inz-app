<?php

use App\Http\Controllers\AuthControllers\LoginController;
use App\Http\Controllers\AuthControllers\RegisterController;
use App\Http\Controllers\AuthControllers\ResendVerificationController;
use App\Http\Controllers\Panel\UsersController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Route::view('/verify_email', 'auth.verify_email')
    ->middleware('guest')
    ->name('verify_email');


Route::post('/register', [RegisterController::class, 'store'])->name('store');
Route::get('/email/verify/{id}/{hash}', function ($id) {
   $user = User::findOrFail($id);

   $user->markEmailAsVerified();

   return redirect('/login')->with('success', 'Konto zostało pomyślnie zweryfikowane.');
})->middleware('signed')->name('verification.verify');

Route::post('/verify_email', ResendVerificationController::class)->middleware(['guest', 'throttle:6,1'])->name('verification.send');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login');

Route::middleware(['auth', 'verified'])->group(function () {
    $user = Auth::user();

    Route::prefix('panel')->group(function () {
        Route::get('/', function () { return view('panel.dashboard'); });
        //Subpage UsersController
        Route::get('/users', UsersController::class);
    });

    // Edit data user
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

});
