<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Constant;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs
     */
    public function listUser()
    {
        $users = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.libelle', '!=', Constant::ROLES['ROOT'])
            ->orderBy('roles.libelle')
            ->select('users.*')
            ->get();

        return view('pages.utilisateurs.listUser', compact('users'));
    }

    /**
     * Formulaire ajout utilisateur
     */
    public function addUser()
    {
        $roles = Role::where('libelle', '!=', Constant::ROLES['ROOT'])->get();
        return view('pages.utilisateurs.addUser', compact('roles'));
    }

    /**
     * Enregistrer un utilisateur
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|exists:roles,id',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role_id'   => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('user.list')
            ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Formulaire édition utilisateur
     */
    public function editUser(string $userId)
    {
        $user = User::findOrFail($userId);
        $roles = Role::where('libelle', '!=', Constant::ROLES['ROOT'])->get();

        return view('pages.utilisateurs.editUser', compact('user', 'roles'));
    }

    /**
     * Mise à jour utilisateur
     */
    public function updateUser(Request $request, string $userId)
    {
        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8',
        ]);

        $user->update([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'role_id'=> $validated['role'],
        ]);

        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        return redirect()
            ->route('user.editUser', $user->id)
            ->with('success', 'Utilisateur modifié avec succès');
    }

    /**
     * Supprimer utilisateur
     */
    public function deleteUser(string $userId)
    {
        User::findOrFail($userId)->delete();

        return redirect()
            ->route('user.list')
            ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Activer / Désactiver utilisateur
     */
    public function changeUserStatut(string $userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'is_active' => ! $user->is_active
        ]);

        return redirect()
            ->route('user.list')
            ->with('success', 'Statut utilisateur changé avec succès');
    }
}
