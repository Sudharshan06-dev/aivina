<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OAuthController extends Controller
{
    public function registerUser(Request $request)
    {
        Log::info('check here');
        Log::error($request);
    }

    public function loginUser()
    {

    }
}
