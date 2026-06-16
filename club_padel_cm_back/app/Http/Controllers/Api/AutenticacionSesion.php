<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AutenticacionSesion extends Controller
{
    /**
     * Handle an incoming authentication request.
     */

    public function store(Request $request)
    {
        try {
            // Validació amb missatges personalitzats
            $validated = $request->validate([
                   'email'     => 'required|string|email',
                    'password' => 'required|string',
                ], [ 'email.required' => 'El correo es obligatorio',
                    'email.email'    => 'El correu no tiene un formato válido',
                    'password.required' => 'La contraseña es obligatória',
                ]
            );

            // Intent d'inici de sessió
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Las credenciales son incorrectas'
                ], 401);
            }

            // Usuari autenticat
            $user = Auth::guard('sanctum')->user();

            // Crear token d'accés
            $token = $user->createToken('auth_token')->plainTextToken;

            // Saber el role, del usuario
            $monitor = Role::find($user->role_id);

            // Resposta JSON
            return response()->json([
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user,
                'success'      => true,
                'monitor'      => $monitor->name == "monitor",
                'status'       => 'Login OK successful',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al tractar los datos',
                'success' => false,
                'error_details' => $e->getMessage(),
            ], 200);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $token = Auth::guard('sanctum')->user()->currentAccessToken();
            $token->delete();

            return response()->json(['message' => 'Logout OK successful']);
        } catch (Exception $e) {
            // GESTIÓ DE L'ERROR
            // Retorna un JSON amb un missatge d'error i un codi d'estat 500
            return response()->json([
                'message' => 'Se ha producido un error al tractar los datos',
                // El següent és opcional i només s'hauria de mostrar en entorns de desenvolupament (APP_DEBUG=true)
                'error_details' => $e->getMessage(),
            ], 200);
        }
    }
}
