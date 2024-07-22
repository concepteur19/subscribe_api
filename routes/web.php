<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/send-test-email', function () {
//     dd( Carbon::now()->toDateString());
//     Mail::raw('This is a test email', function($message) {
//         $message->to('fullstack.web.dev.pro@gmail.com')  // Remplacez par une adresse email valide
//                 ->subject('Test Email');
//     });

//     return 'Test email sent.';
// });
//je veux creer une route pour tester l'authentification


