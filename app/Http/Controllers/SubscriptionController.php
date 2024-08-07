<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\DefaultSubscription;
use App\Models\Notification;
use App\Models\PlanType;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

// use function PHPUnit\Framework\isType;

class SubscriptionController extends Controller
{
    // Create subscription OK
    public function addSubscription(SubscriptionRequest $request)
    {

        try {

            //  creation d'une souscription vide
            $subscription = new Subscription();

            // on récupère la souscription par défaut par son id
            $defaultSub_id = $request->defaultSub_id;

            $planTypeParam = $request->plan_type;
            $planTypeParamInt = (int) $planTypeParam;
            $plan_type = PlanType::find($planTypeParamInt);
            // dd($plan_type);

            if ($defaultSub_id && $plan_type) {

                $defaultSubscription = DefaultSubscription::find($defaultSub_id);

                // on affecte des données à la souscription vide 
                $subscription->amount = $plan_type->amount;
                $subscription->defaultSub_id = $defaultSub_id;
                $subscription->service_name = $defaultSubscription->name;
                $subscription->logo = $defaultSubscription->logo;
            } else {
                $subscription->service_name = $request->service_name;
                // $subscription->logo = $request->logo;
                // $subscription->$plan_type = $planTypeParam;
                $subscription->amount = $request->amount;
            }

            $subscription->user_id = $request->user_id;
            $subscription->plan_type = $planTypeParam;
            $subscription->payment_method = $request->payment_method;
            $subscription->cycle = $request->cycle;
            $subscription->reminder = $request->reminder;



            // Converti start_on en instance de Carbon
            $startOn = Carbon::parse($request->start_on)->copy()->addDay();
            $subscription->start_on = $startOn;
            // dd($startOn);

            // Calculer la date de fin en fonction du cycle
            switch ($request->cycle) {
                case 'monthly':
                    $endOn = $startOn->copy()->addMonth();
                    // dd($endOn);
                    break;
                case 'yearly':
                    $endOn = $startOn->copy()->addYear();
                    // dd($endOn);
                    break;
                case 'semesterly':
                    $endOn = $startOn->copy()->addMonths(6);
                    break;
                default:
                    // Gérer les cas où le cycle n'est pas reconnu
                    throw new \InvalidArgumentException('Invalid subscription cycle.');
            }

            // Assigner la date de fin calculée à l'abonnement
            $subscription->end_on = $endOn->toDateString();

            $subscription->save();

            $user = $subscription->user;

            $endOnMail = Carbon::parse($subscription->end_on)->toFormattedDateString();

            $notificationContent = "Hello {$user->username}, your subscription to {$subscription->service_name} will expire on {$endOnMail}. The renewal amount is {$subscription->amount}\$. Please visit our application for more details: https://Subscribe.com";
            $notification = new Notification();
            $notification->user_id = $request->user_id;
            $notification->subscription_id = $subscription->id;
            $notification->notification_channel = 'email';
            $notification->sent_at = $this->sentAt($endOn, $request->reminder);
            $notification->notification_status = 'pending';
            $notification->notification_content = $notificationContent;

            // Enregistrer la notification
            $notification->save();

            $push = new Notification();
            $push->user_id = $request->user_id;
            $push->subscription_id = $subscription->id;
            $push->sent_at = $endOn; //$this->sentAt($endOn, $request->reminder);
            $push->notification_channel = 'push';
            $push->notification_status = 'pending'; //'false';
            $push->notification_content = '';

            $push->save();

            return response()->json([
                'status' => true,
                'message' => 'Subscription and notifications created successfully'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Read all the subscriptions OK
    public function getSubscriptions()
    {
        try {
            $subscriptions = Subscription::all();
            return response()->json([
                'code' => 200,
                'status' => true,
                'data' => $subscriptions
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Read all subscriptions of the user OK
    public function getUserSubscriptions(User $user)
    {

        try {
            $userId = $user->id;
            $subscriptions = Subscription::where('user_id', $userId)->get();

            $subscriptionsData = $subscriptions->map(function ($subscription) {

                $type = PlanType::where("id", (int) $subscription->plan_type)->select('type')->first();
                // dd($type);

                return [
                    'id' => $subscription->id,
                    'service_name' => $subscription->service_name,
                    'logo' => $subscription->logo,
                    'cycle' => $subscription->cycle,
                    'plan_type' => $type ? $subscription->plan_type : null,
                    'reminder' => $subscription->reminder,
                    'amount' => $subscription->amount,
                    'type' => $type ? $type->type : $subscription->plan_type,
                    'start_on' => $subscription->start_on,
                    'end_on' => $subscription->end_on,
                ];
            });

            $notifications = Notification::where('user_id', $user->id)
                ->where('notification_channel', 'push')
                ->whereHas('subscription')
                ->with('subscription')
                ->get();

            // dd($notifications);

            $notificationsWithSubscriptions = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'notification_status' => $notification->notification_status,
                    'notif_date' => $notification->sent_at,
                    'amount' => $notification->subscription->amount,
                    'end_on' => $notification->subscription->end_on,
                    'reminder' => $notification->subscription->reminder,
                    'logo' => $notification->subscription->logo,
                    'service_name' => $notification->subscription->service_name

                ];
            });

            // 
            $trueNotifications = $notificationsWithSubscriptions->filter(function ($notification) {
                return $notification['notification_status'] === 'true';
            })->values()->toArray();

            // Calculer la somme des montants pour les notifications approuvées
            $approvedAmountSum = $notificationsWithSubscriptions->filter(function ($notification) {
                return $notification['notification_status'] === 'approuved';
            })->sum('amount');


            return response()->json([
                'code' => 200,
                'status' => true,
                'data' => [
                    'subscriptions' => $subscriptionsData,
                    'payments' => $notificationsWithSubscriptions,
                    'notificationsToPush' => $trueNotifications,
                    'totalAmount' => $approvedAmountSum
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // get one subscription OK
    public function getOneSubscription(Subscription $subscription)
    {
        try {
            $type = PlanType::where("id", (int) $subscription->plan_type)->select('type')->first();
            $subscriptionMap = [
                'id' => $subscription->id,
                'defaultSub_id' => $subscription->defaultSub_id,
                'service_name' => $subscription->service_name,
                'logo' => $subscription->logo,
                'cycle' => $subscription->cycle,
                'plan_type' => $type ? $subscription->plan_type : null,
                'reminder' => $subscription->reminder,
                'amount' => $subscription->amount,
                'payment_method' => $subscription->payment_method,
                'type' => $type ? $type->type : $subscription->plan_type,
                'start_on' => $subscription->start_on,
                'end_on' => $subscription->end_on,
            ];

            return response()->json([
                'code' => 200,
                'status' => true,
                'data' => $subscriptionMap
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function editSubscription(Subscription $subscription, Request $request)
{
    try {
        // Find the plan type if provided
        $planTypeParam = $request->plan_type;
        $planTypeParamInt = (int) $planTypeParam;
        $plan_type = PlanType::find($planTypeParamInt);

        // Update amount based on plan type
        if ($plan_type && ($plan_type->id !== $planTypeParam)) {
            $subscription->amount = $plan_type->amount;
        } else {
            if ($request->has('service_name')) {
                $subscription->service_name = $request->service_name;
            }
            if ($request->has('amount')) {
                $subscription->amount = $request->amount;
            }
        }

        // Update subscription details based on request parameters
        if ($planTypeParam) {
            $subscription->plan_type = $planTypeParam;
        }
        if ($request->has('payment_method')) {
            $subscription->payment_method = $request->payment_method;
        }
        if ($request->has('cycle')) {
            $subscription->cycle = $request->cycle;
        }
        if ($request->has('reminder')) {
            $subscription->reminder = $request->reminder;
        }

        // Update start date and calculate end date based on the cycle
        if ($request->has('start_on')) {
            $startOn = Carbon::parse($request->start_on)->copy()->addDay();
            $subscription->start_on = $startOn;

            switch ($subscription->cycle) {
                case 'monthly':
                    $endOn = $startOn->copy()->addMonth();
                    break;
                case 'yearly':
                    $endOn = $startOn->copy()->addYear();
                    break;
                case 'semesterly':
                    $endOn = $startOn->copy()->addMonths(6);
                    break;
                default:
                    throw new \InvalidArgumentException('Invalid subscription cycle.');
            }
            $subscription->end_on = $endOn->toDateString();
        }

        $subscription->save();

        // Update notifications related to the subscription
        $notifications = $subscription->notifications;

        foreach ($notifications as $notification) {
            if ($notification) {
                $user = $notification->user;
                $endOnMail = Carbon::parse($subscription->end_on)->toFormattedDateString();

                $notification->notification_content = "Hello {$user->username}, your subscription to {$subscription->service_name} will expire on {$endOnMail}. The renewal amount is {$subscription->amount}\$. Please visit our application for more details: https://Subscribe.com";

                $notification->sent_at = $this->sentAt($endOn, $subscription->reminder);

                if (Carbon::now()->lt($notification->sent_at)) {
                    $notification->notification_status = 'pending';
                }

                // Save the notification changes
                $notification->save();
            }
        }

        return response()->json([
            'code' => 201,
            'status' => true,
            'message' => 'Subscription updated successfully.'
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'code' => 500,
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}


    //  delete a subscription  OK
    public function deleteOneSubscription(Subscription $subscription)
    {
        try {
            $subscription->delete();
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Subscription deleted succefully.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //  delete all user's subscription ok
    public function deleteAllUserSubscriptions(User $user)
    {
        // return response()->json([]);

        try {
            $user->subscriptions->each(function ($subscription) {
                $subscription->delete();
            });
            $user->save();
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Your subscriptions deleted succefully.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // get expired subscription OK
    public function getExpSubscriptions(User $user)
    {
        $expSubscriptions = [];

        try {
            $notifications = Notification::all()
                ->where('user_id', $user->id)
                ->where('notification_channel', 'push')
                ->where('notification_status', 'false')
                ->where('sent_at', '<=', Carbon::now());

            foreach ($notifications as $push) {
                $expSubscriptions[] = $push->subscription;
            }
            return response()->json([
                'code' => 200,
                'status' => true,
                'data' => $expSubscriptions
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // get default description ok
    public function getDefaultSubscriptions()
    {
        $defaultSubscriptions = DefaultSubscription::all();
        try {

            return response()->json([
                'code' => 200,
                'status' => true,
                'data' => $defaultSubscriptions
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // 
    public function getOneDefaultSubscription(DefaultSubscription $defaultSubscription)
    {
        try {

            // $planTypes = PlanType::where('default_subscription_id', $defaultSubscription->id);
            // $defaultSubscriptionMap = $defaultSubscription;
            // $defaultSubscription->planTypes = $planTypes;

            return response()->json([
                'code' => 200,
                'status' => true,
                'data' => [
                    'id' => $defaultSubscription->id,
                    'name' => $defaultSubscription->name,
                    'logo' => $defaultSubscription->logo,
                    'planTypes' => $defaultSubscription->planTypes
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // 
    private function sentAt($endOn, $reminder)
    {
        return $endOn->copy()->subDays($reminder);
    }
}
