<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(RegisterUser $request) {
        dd('ok');
    }
}
