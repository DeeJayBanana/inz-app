<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __invoke(Request $request) {
        $users = User::with('roles')->get()->sortByDesc('created_at');
        $roles = Role::all();
        return view('panel.users', ['users' => $users, 'roles' => $roles]);
    }

    public function add(Request $request) {
        $data = $request->validate([
            'first_name' => ['required', 'regex:/^[A-Za-zÀ-žżźćńółęąśŻŹĆĄŚĘŁÓŃ\s-]+$/'],
            'last_name' => ['required', 'regex:/^[A-Za-zÀ-žżźćńółęąśŻŹĆĄŚĘŁÓŃ\s-]+$/'],
            'name' => 'required|unique:users|min:3',
            'email' => 'required|email|unique:users',
            'password' => [Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()],
        ]);

        if(!$request->filled('password')) {
            $data['password'] = Hash::make(Str::random(16));
            $data['must_change_password'] = true;
        } else {
            $data['password'] = Hash::make($request->password);
            $data['must_change_password'] = false;
        }

        $user = User::create($data);

        if(isset($request->verifyCheck)) {
            $user->email_verified_at = now();
            $user->save();
        } else {
            event(new Registered($user));
        }

        $user->assignRole($request->input('role'));


//        if($dadta['must_change_password'] == 0) {
//            Password::sendResetLink($request->only('email'));
//        }

        return redirect()->back()->with('success', 'Pomyślnie dodano konto.');
    }

    public function edit($id) {
        return response()->json(User::with('roles')->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        if($user->getRoleNames()->contains('admin') && auth()->id() !== $user->id) {
            return redirect()->back()->with('error', 'Nie możesz edytować konta administratora.');
        }
        //Tap - pozwala wywołąć metode na obiekcie po akcji
        $user->tap()->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ])->syncRoles($request->input('role'));

        return back()->with('success', 'Pomyślnie zaaktualizono dane użytkownika');
    }

    public function delete($id) {
        $user = User::findOrFail($id);
        $logged = auth()->id();

        if($user->id === $logged) {
            return redirect()->back()->with('error', 'Nie możesz usuwać własnego konta.');
        }

        if($user->getRoleNames()->contains('admin')) {
            return redirect()->back()->with('error', 'Nie możesz usunąć konta administratora.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Użytownik został usunięty.');
    }

}
