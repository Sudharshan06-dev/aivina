<?php

namespace App\Services;

use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginHandlerService
{

    private static ?LoginHandlerService $login_handler_instance = null;

    // Static method to access the singleton instance
    public static function getLoginHandlerInstance(): ?LoginHandlerService
    {
        if (self::$login_handler_instance === null) {
            self::$login_handler_instance = new self();
        }
        return self::$login_handler_instance;
    }

    public function registerUser($user_signup_data)
    {
        try {

            $user_signup_validation = ValidationService::getValidationServiceInstance()->validateSignupFields($user_signup_data);

            if(!$user_signup_validation['status']) {
                return response($user_signup_validation['message'], 422);
            }

            //Main functionality
            Users::create([
                'firstname' => $user_signup_data['firstname'],
                'lastname' => $user_signup_data['lastname'],
                'username' => $user_signup_data['username'],
                'email' => $user_signup_data['email'],
                'password' => Hash::make($user_signup_data['password']),
                'created_at' => Carbon::now()
            ]);

            return response(trans('messages.success.signup_successfully'), 200);

        } catch (\Throwable $e) {
            Log::error($e);
            return response(trans('messages.errors.signup_failed'), 422);
        }
    }

    public function handleUserLogin($user_login_data)
    {
        try {

            $user_signup_validation = ValidationService::getValidationServiceInstance()->validateLoginFields($user_login_data);

            if(!($user_signup_validation['status'])) {
                return response($user_signup_validation['message'], 422);
            }

            // Get the authenticated user
            $user = Auth::user();

            // Create an access token
            $token = $user->createToken('Access Token')->accessToken;

            // Update the last login time
            $user->last_login_at = Carbon::now();
            $user->save();

            $user['access_token'] = $token;

            return response()->json([
                'success' => true,
                'user_data' => $user,
            ]);

        } catch (\Throwable $e) {
            Log::error($e);
            return response(trans('messages.errors.signup_failed'), 422);
        }
    }

}