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

    public function googleRegistration(Request $request)
    {
        try {
            $user_ = User::where('email', $request->email)->first();

            if ($user_) {
                // Auth::login($user_);
                $token = $user_->createToken('Google OAuth Token')->plainTextToken;
                return response()->json([
                    'code' => 200,
                    'status' => true,
                    'message' => 'Utilisateur connecté.',

                    'data' => [
                        'id' => $user_->id,
                        'username' => $user_->username,
                        'photo' => $user_->photo,
                        'phone_number' => $user_->phone_number,
                        'email' => $user_->email,
                        'token' => $token
                    ]
                ]);
            } else {
                $randomPassword = Str::random(12);
                $user = new User();
                $user->username = $request->username;
                $user->email = $request->email;
                $user->password = $randomPassword;
                $user->save();

                return response()->json([
                    'code' => 200,
                    'message' => 'Utilisateur enregistré avec succès!!!',
                    'data' => $user,

                ]);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
