<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;

use \App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
 
class AuthenticationController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'signup']]);
    }
 
    public function login(Request $request)
    {
        // credenciales para loguear al usuario
        $credentials = $request->only('email', 'password');

        try {
            $user = User::where(['email' => $request["email"]])->first(['email', 'id', 'name']);

            if($user)
            {
                //token con campos definidos
                // si los datos de login no son correctos
                if ( ! $token = JWTAuth::fromUser($user, ['email' => $user->email, 'id' => $user->id, 'name' => $user->name]) ) 
                {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            }
            else 
            {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }   
        } 
        catch (JWTException $e) 
        {
            // si no se puede crear el token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
 
        // todo bien devuelve el token
        return response()->json(compact('token'));
    }

    public function signup(Request $request)
    {
        $user = User::where(['email' => $request["email"]])->exists();
        if($user)
        {
            return response()->json(['msg' => "El usuario con email {$request->email} ya existe"], 400);
        }
        $user = new User;
        $user->create($request->input());
        return response()->json(['msg' => 'Usuario registrado correctamente']);
    }
}