<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Jobs\SendNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/googleRegister', [RegisterController::class, 'googleAuth']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// mettre ici les routes dont l'utilisateur ne peut avoir accès qu'après s'être connecté
Route::middleware(['auth:sanctum'])->group(function () {

    // Modification des informations de l'utilisateur
    Route::post('/users/edit/{user}', [UserController::class, 'editUser']);
    // retourner l'utilisateur connecté 
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD sur les souscriptions
    Route::post('/subscriptions/add', [SubscriptionController::class, 'addSubscription']); // ui ok
    // Route::get('/subscriptions', [SubscriptionController::class, 'getSubscriptions']);
    Route::get('/subscriptions/{user}', [SubscriptionController::class, 'getUserSubscriptions']); //ui ok
    Route::get('/subscriptions/subscription/{subscription}', [SubscriptionController::class, 'getOneSubscription']); //ui ok
    Route::put('/subscriptions/edit/{subscription}', [SubscriptionController::class, 'editSubscription']); // ui ok
    Route::delete('/subscriptions/delete/{subscription}', [SubscriptionController::class, 'deleteOneSubscription']); //ui ok
    Route::delete('/subscriptions/user_delete/{user}', [SubscriptionController::class, 'deleteAllUserSubscriptions']);

    // recupérer les souscription dont la due date est arrivé à expiration OK
    Route::get('/subscriptions/expired/{user}', [SubscriptionController::class, 'getExpSubscriptions']); //ui ok

    // récupérer toutes les souscriptions par défaut
    Route::post('/subscriptions/defaultSubscriptions', [SubscriptionController::class, 'getDefaultSubscriptions']); //ui ok
    // récupérer une souscription par défaut
    Route::get('/subscriptions/defaultSubscriptions/{defaultSubscription}', [SubscriptionController::class, 'getOneDefaultSubscription']);

    // Modification du statut d'une notif push OK
    Route::put('/subscriptions/notificationUpdate/{notification}', [NotificationController::class, 'updateNotification']); //ui ok /subscriptions/notifications/Update
    Route::post('/subscriptions/notifications/Update', [NotificationController::class, 'updateNotifications']); //ui ok 

    // récupérer les notifications
    Route::get('/user/notifications/{user}', [NotificationController::class, 'getUserNotifications']); //


    Route::get('/sendEmail', [SendNotificationJob::class, 'handle']);

    Route::post('/logout', [LoginController::class, 'logout']);
    
});