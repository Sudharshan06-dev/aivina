<?php

namespace App\Services;

use App\Interfaces\SSOProviderInterface;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Mockery\Exception;
use Illuminate\Http\Request;

class GoogleSSOAdapter implements SSOProviderInterface
{
    const TYPE_LOGIN = 'login';

    const TYPE_SIGNUP = 'signup';

    private $google_user = null;

    private $user = null;

    private $logging_in_user = null;

    public function redirectToProvider(Request $request)
    {
        return Socialite::driver('google')->with(['state' => $request->type])->redirect();
    }

    public function handleProviderCallback()
    {
        try {

            $this->google_user = Socialite::driver('google')->stateless()->user();

            //Get the user details we have
            $this->user = Users::where([
                'email' => $this->google_user->getEmail(),
                'is_deleted' => config('constants.IS_DELETED_NO')
            ])->first();

            // Retrieve the type from the state
            $loginType = request('state'); // 'login' or 'signup'

            if($loginType == self::TYPE_LOGIN) {
                return $this->_loginSSOUser();
            }
            elseif ($loginType == self::TYPE_SIGNUP) {
                return $this->_signUpSSOUser();
            }
            else {
                return redirect("http://localhost:4200/login?error=Invalid authentication type");
            }

        } catch (Exception $e) {
            Log::error('GoogleSSOAdapter::handleProviderCallback');
            Log::error($e);
            return redirect("http://localhost:4200/login?error=Authentication failed");
        }
    }

    private function _loginSSOUser()
    {
        try {

            if(!$this->user) {
                return redirect("http://localhost:4200/login?error=User does not exist. Signup before logging in");
            }

            if($this->user->google_id != $this->google_user->getId()) {
                return redirect("http://localhost:4200/login?error=Login Failed. Check the user valid");
            }

            //Assign the new user to the current user if valid
            $this->logging_in_user = $this->user;

            //Update the last login at to the current time
            $this->user->last_login_at = Carbon::now();

            return redirect("http://localhost:4200/auth/google/callback?token=" . $this->_createTokenForUser());

        } catch (Exception $exception) {
            Log::error('GoogleSSOAdapter::_loginSSOUser');
            Log::error($exception);
            return redirect("http://localhost:4200/login?error=Login failed");
        }
    }

    private function _signUpSSOUser()
    {
        try {

            //Check if the user already exists
            if($this->user) {
                return redirect("http://localhost:4200/login?error=User already exist. Try to login");
            }

            //Check for all the information that is required is present
            $transformed_user_data_for_validation = [
                'firstname' => $this->google_user->offsetGet('given_name'),
                'lastname' => $this->google_user->offsetGet('family_name'),
                'username' => $this->google_user->email,
                'email' => $this->google_user->email,
                'password' => $this->google_user->getId()
            ];

            $user_signup_validation = ValidationService::getValidationServiceInstance()->validateSignupFields($transformed_user_data_for_validation);

            if(!$user_signup_validation['status']) {
                $message = $user_signup_validation['message'];
                return redirect("http://localhost:4200/login?error=$message");
            }

            DB::connection('meta')->beginTransaction();

            //Create the user in the database
            $this->logging_in_user = Users::create([
                'firstname' => $this->google_user->offsetGet('given_name'),
                'lastname' => $this->google_user->offsetGet('family_name'),
                'username' => $this->google_user->email,
                'email' => $this->google_user->email,
                'last_login_at' => Carbon::now(),
                'google_id' => $this->google_user->getId()
            ]);

            $email_content = view('emails.welcome_email')->render();

            $email_sent = NotificationService::getNotificationService()->triggerSqsEmail($this->google_user->email, $email_content);

            if(!$email_sent) {
                Log::critical('GoogleSSOAdapter::_signUpSSOUser');
                Log::critical('Email not sent, please check the error');
            }

            DB::connection('meta')->commit();

            return redirect("http://localhost:4200/auth/google/callback?token=" . $this->_createTokenForUser());

        } catch (\Throwable $exception) {
            Log::error('GoogleSSOAdapter::_signUpSSOUser');
            Log::error($exception);
            DB::connection('meta')->rollBack();
            return redirect("http://localhost:4200/login?error=Login failed");
        }
    }

    private function _createTokenForUser()
    {
        return $this->logging_in_user->createToken('auth_token')->accessToken;
    }
}
