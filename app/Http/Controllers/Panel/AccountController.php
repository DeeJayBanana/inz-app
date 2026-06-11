<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index() {
        return view('panel.account');
    }

    public function update(Request $request) {
        $user = Auth::user();

        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if($request->hasFile('avatar')) {
            if($user->avatar && Storage::disk('public/avatars')->exists($user->avatar)) {
                Storage::disk('public/avatars')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        } elseif (is_null($request->input('avatar'))) {
            unset($data['avatar']);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Dane zostały pomyślenie zaaktualizowane.');
    }
}
