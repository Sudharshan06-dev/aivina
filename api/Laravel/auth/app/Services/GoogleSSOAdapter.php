<?php

namespace App\Services;

use App\Interfaces\SSOProviderInterface;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Mockery\Exception;
use Illuminate\Http\Request;

class GoogleSSOAdapter implements SSOProviderInterface
{
    private string $login_type;

    public function redirectToProvider(Request $request)
    {
        $this->login_type = $request->type;
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->user();

            //Get the user details we have
            $user = Users::where([
                'email' => $googleUser->getEmail(),
                'is_deleted' => config('constants.IS_DELETED_NO')
            ])->first();

            //Check for already user signed up
            /*if($this->login_type == self::TYPE_SIGNUP && $user) {
                Log::error('GoogleSSOAdapter::handleProviderCallback');
                return redirect("http://localhost:4200/login?error=Already signedup");
            }*/

            // Find or create user
            $user = Users::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'firstname' => $googleUser->offsetGet('given_name'),
                    'lastname' => $googleUser->offsetGet('family_name'),
                    'username' => $googleUser->email,
                    'email' => $googleUser->email,
                    'last_login_at' => Carbon::now(),
                    'google_id' => $googleUser->getId()
                ]
            );

            // Create token
            $token = $user->createToken('auth_token')->accessToken;

            // Redirect back to frontend with token
            return redirect("http://localhost:4200/auth/google/callback?token=" . $token);

        } catch (Exception $e) {
            Log::error('GoogleSSOAdapter::handleProviderCallback');
            Log::error($e);
            return redirect("http://localhost:4200/login?error=Authentication failed");
        }
    }
}
