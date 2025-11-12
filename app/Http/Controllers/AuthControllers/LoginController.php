<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        try {
            if (Auth::attempt($credentials)) {

                $request->session()->regenerate();

                if (!Auth::user()->hasVerifiedEmail()) {
                    Auth::logout();
                    return back()->with('error', 'Twoje konto nie zostało zweryfikowane.');
                }


                return redirect()->intended('/panel')->with('success', 'Pomyślnie zalogowano do panelu.');
            } else {
                return back()->withErrors([
                    'login' => 'Nieprawidłowy adres email lub hasło'
                ])->onlyInput('email, password');
            }
        } catch(\Exception $e) {
            return back()->with('error', "Wystąpił nieoczekiwany błąd. Spróbuj ponowanie później bądź skontaktuj się z administratorem.");
        }


    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Pomyślnie wylogowano.');
    }
}
