<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegistroUser extends Controller

{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [   'name'      => 'required|string|max:255',
                    'lastname'  => 'required|string|max:255',
                    'email'     => 'required|string|email|max:255|unique:users', 
                    'phone'     => 'nullable|string', 
                    'password'  => 'required|string|min:4',
                ], [
                    'name.required'      => 'El nombre es obligatorio',
                    'lastname.required'  => 'El apelido es obligatorio',
                    'email.required'     => 'El email es obligatorio',
                    'email.email'        => 'El email no tiene un formato correcto',
                    'email.unique'       => 'Este email ya esta registrado',
                    'password.required'  => 'La constraseña es obligatória.',
                    'password.min'       => 'La contraseá ha de tener al menos 4 carácters.',
                ]
            );

            // Creació de l'usuari
            $data = [
                'name'              => $validated['name'],
                'lastname'          => $validated['lastname'],
                'email'             => $validated['email'],
                'email_verified_at' => now(),
                'password'          => Hash::make($validated['password']),
                'role_id'           => Role::where('name', 'cliente')->value('id'),
            ];

            if (!empty($validated['phone'])) {
                $data['phone'] = $validated['phone'];
            }

            $user = User::create($data);

            $token = $user->createToken('auth_token')->plainTextToken;  // Crea el token en 'personal_acces_tokens'

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'success' => true,
                'user' => $user
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Se ha producido un error al tractar los datos',
                'error_details' => $e->getMessage(),
            ], 200);
        }
    }
}
