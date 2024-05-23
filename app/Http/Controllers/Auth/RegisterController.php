<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Exception;

class RegisterController extends Controller
{
    public function register(RegisterUser $request)
    {

        try {
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = $request->password;
            // dd($request->password);
            $user->save();

            return response()->json([
                'code' => 200,
                'message' => 'Utilisateur enregistré avec succès!!!',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
