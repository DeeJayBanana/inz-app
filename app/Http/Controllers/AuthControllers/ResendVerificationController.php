<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ResendVerificationController extends Controller
{
    public function __invoke(Request $request) {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Nie znaleziono użytkownika o podanym adresie e-mail.');
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Ten adres e-mail został już zweryfikowany.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Nowy link weryfikacyjny został wysłany.');
    }
}
