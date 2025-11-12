<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __invoke(Request $request) {
        $users = User::all()->sortByDesc('created_at');
        return view('panel.users', compact('users'));
    }

    public function edit($id) {
        return response()->json(User::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id)->update($request->only(['first_name', 'last_name', 'email', 'role']));

        return back()->with('success', 'Pomyślnie zaaktualizono dane użytkownika');
    }

}
