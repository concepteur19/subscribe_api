<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // OK
     public function updateNotification(User $user, Request $request)
     {
        $notification = Notification::where('user_id', $user->id)
            ->where('subscription_id', $request->subscription_id)
            ->where('notification_channel', 'push')
            ->first();

         try {
            $notification->notification_status = 'false';
            $notification->save();
             return response()->json([
                 'code' => 201,
                 'status' => true,
                 'push' => $notification
             ]);
         } catch (\Throwable $th) {
             return response()->json([
                 'status' => false,
                 'message' => $th->getMessage()
             ], 500);
         }
     }
}
