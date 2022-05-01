<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class LoginJwtController extends Controller
{

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator =  Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()){
            $message = new ApiMessages('Insira os campos corretos');
            return response()->json($message->getMessage(), 401);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            $message = new ApiMessages('Não autorizado');
            return response()->json($message->getMessage(), 401);
        }

        return $this->respondWithToken($token);
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function logout() {
      auth('api')->logout();
      return response()->json(
          ['message' => 'Logout com sucesso!'], 200
      );
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }
}
