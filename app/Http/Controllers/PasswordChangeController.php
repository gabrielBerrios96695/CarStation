<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PasswordChangeController extends Controller
{
    /**
     * Mostrar el formulario de cambio de contraseña.
     *
     * @return \Illuminate\View\View
     */
    public function showChangeForm()
    {
        return view('password-change');
    }

    /**
     * Actualizar la contraseña del usuario autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar que la contraseña actual es correcta
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta.'],
            ]);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->password_changed_at = now(); // Actualizar la fecha de cambio de contraseña
        $user->save();

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('dashboard')->with('status', 'Contraseña cambiada con éxito.');
    }
}
