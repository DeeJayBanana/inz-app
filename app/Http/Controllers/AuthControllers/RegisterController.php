<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function store(Request $request) {
        try {
            $data = $request->validate([
                'first_name' => ['required', 'regex:/^[A-Za-zÀ-žżźćńółęąśŻŹĆĄŚĘŁÓŃ\s-]+$/'],
                'last_name' => ['required', 'regex:/^[A-Za-zÀ-žżźćńółęąśŻŹĆĄŚĘŁÓŃ\s-]+$/'],
                'name' => 'required|unique:users|min:3',
                'email' => 'required|email|unique:users',
                'password' => ['required', 'confirmed', Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()],
                'password_confirmation' => 'required|same:password',
            ]);

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);
            event(new Registered($user));
        } catch (\Exception $e) {
            return back()->with('error', "Wystąpił nieoczekiwany błąd. Spróbuj ponowanie później bądź skontaktuj się z administratorem.");
        }

        return redirect('/login')->with('warning', 'Konto zostało utworzone. Sprawdź skrzynkę pocztową, aby je zweryfikować. ');
    }
}
