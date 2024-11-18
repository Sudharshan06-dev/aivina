<?php

namespace App\Services;

use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ValidationService
{
    private static ?ValidationService $validation_service = null;

    // Static method to access the singleton instance
    public static function getValidationServiceInstance(): ?ValidationService
    {
        if (self::$validation_service === null) {
            self::$validation_service = new self();
        }
        return self::$validation_service;
    }

    public function validateSignupFields($user_signup_data): array
    {
        try {

            //Check all mandatory fields exists
            foreach (config('constants.MANDATORY_FIELDS.SIGNUP_FIELDS') as $field) {
                if (empty($user_signup_data[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {

                return ['status' => false, 'message' => [
                    'title' => 'Validation Error',
                    'message' => 'The following fields are required: ' . implode(', ', $missingFields)
                ]];
            }

            //Username already exists
            if(!empty($this->_checkUserExists('username', $user_signup_data['username']))) {
                return ['status' => false, 'message' => trans('messages.errors.username_exists')];
            }

            //Email already exists
            if(!empty($this->_checkUserExists('email', $user_signup_data['email']))) {
                return ['status' => false, 'message' => trans('messages.errors.email_exists')];
            }

            //Username length exceeded
            if(strlen($user_signup_data['username']) > config('constants.USERNAME_MAX_LENGTH')) {
                return ['status' => false, 'message' => trans('messages.errors.username_maxlength_exceeded')];
            }

            return ['status' => true];

        } catch (\Throwable $exception) {
            Log::error($exception);
            return ['status' => false, 'message' => trans('messages.errors.signup_failed')];
        }
    }

    public function validateLoginFields($user_login_data): array
    {
        try {

            //Check all mandatory fields exists
            foreach (config('constants.MANDATORY_FIELDS.LOGIN_FIELDS') as $field) {
                if (empty($user_login_data[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {

                return ['status' => false, 'message' => [
                    'title' => 'Validation Error',
                    'description' => 'The following fields are required: ' . implode(', ', $missingFields)
                ]];
            }


            //Username already exists
            if(is_null($user_login_data['username'])) {
                return ['status' => false, 'message' => trans('messages.errors.username_password_invalid')];
            }


            Log::error(Auth::attempt(['username' => $user_login_data['username'], 'password' => $user_login_data['password']]));

            if (!(Auth::attempt(['username' => $user_login_data['username'], 'password' => $user_login_data['password']]))) {
                return ['status' => false, 'message' => trans('messages.errors.username_password_invalid')];
            }

            return ['status' => true];

        } catch (\Throwable $exception) {
            Log::error($exception);
            return ['status' => false, 'message' => trans('messages.errors.login_failed')];
        }
    }


    private function _checkUserExists($field_key, $field_value)
    {

        return Users::where([
            $field_key => $field_value,
            'is_deleted' => config('constants.IS_DELETED_NO')
        ])->exists();
    }

}
