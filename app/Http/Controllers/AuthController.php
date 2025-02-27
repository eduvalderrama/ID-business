<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'in:admin,vendedor'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $this->userService->registerUser($request->all());

        return response()->json([
            'token' => $user->createToken('api_token')->plainTextToken,
            'message' => 'Usuario registrado exitosamente'
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $token = $this->userService->authenticateUser($request->email, $request->password);

        if (!$token) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
                'errors' => ['email' => ['Las credenciales no son correctas.']]
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'message' => 'Inicio de sesión exitoso'
        ]);
    }

    public function logout(Request $request)
    {
        $this->userService->logoutUser($request->user());

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}
