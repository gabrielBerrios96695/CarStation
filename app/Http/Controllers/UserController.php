<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Mostrar una lista de los usuarios.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Recuperar todos los usuarios, incluidos los marcados como eliminados (status = 0)
        $users = User::all(); // Obtener todos los usuarios sin ordenación

        return view('livewire/users.index', compact('users'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('livewire/users.create');
    }

    /**
     * Mostrar el formulario para editar un usuario existente.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('livewire/users.edit', compact('user'));
    }

    /**
     * Actualizar un usuario existente.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|integer',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }


    /**
     * Marcar un usuario como eliminado (cambiar el estado a 0).
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Cambiar el estado a 0 para marcar como eliminado
        $user->update(['status' => 0]);

        return redirect()->route('users.index')->with('success', 'Usuario marcado como eliminado correctamente');
    }

    /**
     * Almacenar un nuevo usuario.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 1, // Por defecto, el usuario está activo
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }
    public function restore(User $user)
    {
        // Restaurar el estado del usuario a 1
        $user->status = 1;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Estado del usuario restaurado correctamente');
    }

}
