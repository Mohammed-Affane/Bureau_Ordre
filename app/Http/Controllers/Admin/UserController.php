<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboards.admin.users.index', compact('users'));
    }

    public function create()
    {
        // Assuming you have a Role model and a roles table
        $roles = Role::all(); // Fetch all roles from the database
        // Pass the roles to the view for the select dropdown
        return view('dashboards.admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
       // Validation des données
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|exists:roles,id',  // Vérifie que le rôle existe en BDD
    ]);

    // Hash du mot de passe
    $validated['password'] = bcrypt($validated['password']);

    // Création de l'utilisateur
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password'],
    ]);

    // Récupération du rôle par id depuis la base
    $role = SpatieRole::find($validated['role']);

    // Assignation du rôle à l'utilisateur
    $user->assignRole($role->name);

    // Redirection avec message succès
    return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé et rôle assigné.');
    }

    public function edit($id)
    {
    $user = User::findOrFail($id);
    $roles = Role::all(); // pour le dropdown
    return view('dashboards.admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6',
        'role' => 'required|exists:roles,id',
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];

    if (!empty($validated['password'])) {
        $user->password = bcrypt($validated['password']);
    }

    $user->save();

    // Synchroniser le rôle (enlève l'ancien, ajoute le nouveau)
    $role = SpatieRole::find($validated['role']);
    $user->syncRoles([$role->name]);

    return redirect()->route('admin.users.index')->with('success', 'Utilisateur modifié.');
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete(); // Soft delete (remplit deleted_at)
    
    return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
}

}
