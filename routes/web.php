<?php

use App\Http\Controllers\AuthControllers\LoginController;
use App\Http\Controllers\AuthControllers\RegisterController;
use App\Http\Controllers\AuthControllers\ResendVerificationController;
use App\Http\Controllers\Panel\AccountController;
use App\Http\Controllers\Panel\RoleController;
use App\Http\Controllers\Panel\UsersController;
use App\Http\Controllers\Panel\VideoAcceptController;
use App\Http\Controllers\Panel\VideoController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');

//Register Form
Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])->name('store');

//Login Form
Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

//Verify Email Form
Route::view('/verify_email', 'auth.verify_email')
    ->middleware('guest')
    ->name('verify_email');

Route::get('/email/verify/{id}/{hash}', function ($id) {
   $user = User::findOrFail($id);

   $user->markEmailAsVerified();

   return redirect('/login')->with('success', 'Konto zostało pomyślnie zweryfikowane.');
})->middleware('signed')->name('verification.verify');

Route::post('/verify_email', ResendVerificationController::class)->middleware(['guest', 'throttle:6,1'])->name('verification.send');

//Administration panel for users auth and verified
Route::middleware(['auth', 'verified'])->group(function () {
    $user = Auth::user();

    Route::prefix('panel')->group(function () {
        Route::get('/', function () { return view('panel.dashboard'); });
        Route::get('/analyse', [VideoController::class, 'index']);

        Route::get('/videos/{uuid}/accept', [VideoAcceptController::class, 'accept'])
            ->middleware('permission:video.moderate')
            ->name('videos.accept');

        Route::get('/videos/{uuid}/reject', [VideoAcceptController::class, 'reject'])
            ->middleware('permission:videos.moderate')
            ->name('videos.reject');

        Route::get('/videos', VideoAcceptController::class)
            ->middleware('permission:videos.view');

        Route::get('/account', [AccountController::class, 'index']);
        Route::put('/save_account', [AccountController::class, 'update'])->name('account.update');
    });

    Route::prefix('panel/admin')->group(function () {
        //Subpage UsersController
        Route::get('/users', UsersController::class)
            ->middleware('permission:users.view');
    });

    // Edit data user
    Route::post('/users/add', [UsersController::class, 'add'])
        ->middleware('permission:users.add')
        ->name('users.add');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])
        ->middleware('permission:users.edit')
        ->name('users.edit');
    Route::put('/users/{id}', [UsersController::class, 'update'])
        ->middleware('permission:users.update')
        ->name('users.update');
    Route::delete('/users/{id}', [UsersController::class, 'delete'])
        ->middleware('permission:users.delete')
        ->name('users.delete');

    //Roles
    Route::post('/roles/store', [RoleController::class, 'store'])
        ->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])
        ->name('roles.edit');
    Route::put('/roles/{id}/update', [RoleController::class, 'update'])
        ->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'delete'])
        ->name('roles.delete');

    //Video
    Route::post('/video/store', [VideoController::class, 'store'])->name('video.store');
    Route::post('/video/upload', [VideoController::class, 'uploadChunk'])->name('video.chunk');
    Route::post('/video/finalize', [VideoController::class, 'finalize'])->name('video.finalize');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

});

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');
