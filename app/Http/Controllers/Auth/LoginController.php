<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LogUserRequest $request)
    {
        try {

            if (Auth::attempt($request->only(['email', 'password']))) {

                $user = User::where('email', $request->email)->first();
                $token = $user->createToken("LA_CLE_SECRETE_DE_CONCEPTEUR_JS")->plainTextToken;

                return response()->json([
                    'code' => 200,
                    'status' => true,
                    'message' => 'Utilisateur connecté.',

                    'User' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'token' => $token
                    ]
                ]);
            } else {
                return response()->json([
                    'code' => 403,
                    'status' => false,
                    'message' => 'Informations non valides.'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
