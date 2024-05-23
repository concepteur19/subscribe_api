<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SubscriptionController;
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

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// mettre ici les routes dont l'utilisateur ne peut avoir accès qu'après s'être connecté
Route::middleware(['auth:sanctum'])->group(function () {

    // CRUD sur les souscriptions
    Route::post('/subscriptions/add', [SubscriptionController::class, 'addSubscription']);
    // Route::get('/subscriptions', [SubscriptionController::class, 'getSubscriptions']);
    Route::get('/subscriptions/{user}', [SubscriptionController::class, 'getUserSubscriptions']);
    Route::get('/subscriptions/subscription/{subscription}', [SubscriptionController::class, 'getOneSubscription']);
    Route::put('/subscriptions/edit/{subscription}', [SubscriptionController::class, 'editSubscription']);
    Route::delete('/subscriptions/delete/{subscription}', [SubscriptionController::class, 'deleteOneSubscription']);
    Route::delete('/subscriptions/user_delete/{user}', [SubscriptionController::class, 'deleteAllUserSubscriptions']);

    // Route::get('/sendEmail', [SendNotificationJob::class, 'handle']);

    // retourner l'utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
