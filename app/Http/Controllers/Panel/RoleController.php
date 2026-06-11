<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function store(Request $request) {
        $request->validate([
            'role_name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->role_name]);

        if($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->back()->with('success', 'Pomyślnie utworzyłeś role');
    }

    public function edit($id){
        return response()->json(Role::with('permissions')->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
            'permissions' => 'array'
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name
        ]);

        //$request->nazwa_pola vs $request->input('pole', []);
        //$request->input('pole', []); jest dobra dla checkbów, mówimy mu że jeżeli nic nie jest zaznaczone wtedy masz pustą tablice,
        //$request->nazwa_pola a tak może być syncPermissions(null) może wtedy wyrzucić błąd
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->back()->with('success', "Rola '{$role->name}' została pomyślnie zaktualizowana");
    }

    public function delete($id) {
        $protectedRoles = ['administrator'];

        $role = Role::findOrFail($id);

        if(in_array($role->name, $protectedRoles)) {
            return redirect()->back()->with('error', 'Nie masz uprawnień do usunięcia roli systemowej');
        }
        $role->delete();
        return redirect()->back()->with('success', 'Rola została usunięta');
    }
}
