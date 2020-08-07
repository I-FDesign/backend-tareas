<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register an User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request)
    {

        $messages = [
            'email' => 'El email no es valido',
            'email.unique' => 'El email ingresado ya se encuentra registrado',
            'min' => 'La contraseña debe contener por lo menos :attribute caracteres'
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users',
            'password' => 'min:6'
        ], $messages);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $user = new User();

        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);

        $user->save();

        return response()->json([
            'message' => 'Te has registrado correctamente'
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {

        $user = auth()->user();

        $user['password'] = ':)';

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime('now') + auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }
}
