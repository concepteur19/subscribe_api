<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
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
}
