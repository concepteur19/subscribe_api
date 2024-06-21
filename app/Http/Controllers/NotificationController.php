<?php

namespace App\Http\Controllers;

use App\Models\Notification;
// use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function getUserNotifications(User $user)
    {
        // dd($user);
        try {
            $notifications = Notification::where('user_id', $user->id)
                ->where('notification_channel', 'push')
                // ->where('notification_status', 'true')
                ->whereHas('subscription')
                ->with('subscription')
                // ->select('amount', 'end_on', 'subscription_id', 'reminder', 'logo')
                // ->where('notification_status', '!=', 'pending')
                ->get();

            // dd($notifications);

            $notificationsWithSubscriptions = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'notification_status' => $notification->notification_status,
                    'notif_date' => $notification->sent_at,
                    // 'subscription_id' => $notification->subscription->id,
                    'amount' => $notification->subscription->amount,
                    'end_on' => $notification->subscription->end_on,
                    'reminder' => $notification->subscription->reminder,
                    'logo' => $notification->subscription->logo

                ];
            });

            $partitioned = $notificationsWithSubscriptions->partition(function ($notification) {
                return $notification['notification_status'] === 'true';
            });

            // Notifications avec le statut 'true'
            $trueNotifications = $partitioned[0];

            // Toutes les notifications
            $allNotifications = $notificationsWithSubscriptions;

            return response()->json([
                'code' => 200,
                'status' => true,
                'data' =>  [
                    'payments' => $allNotifications,
                    'notificationsToPush' => $trueNotifications,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    // OK
    public function updateNotification(Notification $notification, Request $request) //User $user) //, Request $request)
    {
        // $notification = Notification::where('user_id', $user->id)
        //     ->where('subscription_id', $request->subscription_id)
        //     ->where('notification_channel', 'push')
        //     ->first();

        try {
            $notification->notification_status = $request->status;
            $notification->save();
            return response()->json([
                'code' => 201,
                'status' => true,
                'data' => $notification
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
