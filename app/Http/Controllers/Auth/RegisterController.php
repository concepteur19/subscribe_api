<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
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
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function googleAuth(Request $request)
    {
        try {
            $user_ = User::where('email', $request->email)->first();

            if ($user_) {
                return $this->login($user_);
            } else {
                $randomPassword = Str::random(12);
                $user = new User();
                $user->username = $request->username;
                $user->email = $request->email;
                $user->photo = $request->photo;
                $user->password = $randomPassword;
                $user->save();

                return $this->login($user);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    private function login($user)
    {
        $token = $user->createToken('Google OAuth Token')->plainTextToken;
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Utilisateur connecté.',

            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'photo' => $user->photo,
                'phone_number' => $user->phone_number,
                'email' => $user->email,
                'token' => $token
            ]
        ]);
    }
}
