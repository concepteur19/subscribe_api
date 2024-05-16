<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogUserRequest;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(RegisterUser $request)
    {

        try {
            $user = new User;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = $request->password;

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

    // public function login(LogUserRequest $request)
    // {

    //     try {

    //         if (Auth::attempt($request->only(['email', 'password']))) {

    //             $user = User::where('email', $request->email)->first();
    //             $token = $user->createToken("LA_CLE_SECRETE_DE_CONCEPTEUR_JS")->plainTextToken;

    //             return response()->json([
    //                 'code' => 200,
    //                 'status' => true,
    //                 'message' => 'Utilisateur connecté.',

    //                 'user' => [
    //                     'id' => $user->id,
    //                     'username' => $user->username,
    //                     'email' => $user->email,
    //                     'token' => $token
    //                 ]
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'code' => 403,
    //                 'message' => 'Informations non valides.'
    //             ]);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $th->getMessage()
    //         ], 500);
    //     }
    // }

    // public function forgotPassword(Request $request)
    // {
    //     try {
    //         $user = User::all()->where('id', $request->id)->first();
    //         if ($user->reset_password_code == $request->code) {
    //             $new_password = Str::random(8);
    //             $user->password =  Hash::make($new_password);
    //             if ($user->save()) {
    //                 $message = "Your new Password is $new_password";
    //                 $account_sid = getenv("TWILIO_SID");
    //                 $auth_token = getenv("TWILIO_TOKEN");
    //                 $twilio_number = getenv("TWILIO_FROM");
    //                 $client = new Twillio($account_sid, $auth_token);
    //                 $client->messages->create($user->numero, [
    //                     'from' => $twilio_number,
    //                     'body' => $message
    //                 ]);
    //             } else {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => "failed to change password retry",
    //                 ], 401);
    //             }
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => "Password Updated Successfully",
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => "Incorrect code",
    //             ], 400);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $th->getMessage()
    //         ], 500);
    //     }
    // }


    // public function resendCode(Request $request)
    // {
    //     try {
    //         $user = User::all()->where('id', $request->id)->first();
    //         $message = "You want to reset your password your verification code is : $user->reset_password_code";
    //         $account_sid = getenv("TWILIO_SID");
    //         $auth_token = getenv("TWILIO_TOKEN");
    //         $twilio_number = getenv("TWILIO_FROM");
    //         $client = new Twillio($account_sid, $auth_token);
    //         $client->messages->create($user->numero, [
    //             'from' => $twilio_number,
    //             'body' => $message
    //         ]);

    //         return response()->json([
    //             'status' => true,
    //             'message' => "Code renvoyé avec succès",
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $th->getMessage()
    //         ], 500);
    //     }
    // }
}
