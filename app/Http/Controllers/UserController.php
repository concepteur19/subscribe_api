<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function editUser(User $user, UpdateUserRequest $request)
    {
        try {
            /** @var UploadedFile|null $photo */
            $photo = $request->file('image');

            if ($photo !== null && !$photo->getError()) {
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }
                $photoPath = $photo->store("userPhoto/id_{$user->id}", "public");
                $user->photo = $photoPath;
            }

            $user->username = $request->input('username', $user->username);
            $user->phone_number = $request->input('phone_number', $user->phone_number);
    
            $user->save(); 

            return response()->json([
                'code' => 200,
                'message' => 'Utilisateur modifié avec succès!!!',
                'User' => $user
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

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
