<?php

namespace App\Http\Controllers;

use App\Services\LoginHandlerService;
use App\Services\SSOFactory;
use Illuminate\Http\Request;
class OAuthController extends Controller
{
    protected $data;
    public function __construct(Request $request)
    {
        $this->data = $request->all();
    }

    public function postRegisterUser()
    {
        if(!empty($this->data)) {
            return LoginHandlerService::getLoginHandlerInstance()->registerUser($this->data);
        }

        return response(trans('messages.errors.signup_failed'), 422);
    }

    public function postHandleUserLogin()
    {
        if(!empty($this->data)) {
            return LoginHandlerService::getLoginHandlerInstance()->handleUserLogin($this->data);
        }

        return response(trans('messages.errors.login_failed'), 422);
    }

    public function handleSsoRedirect(Request $request)
    {
        $ssoProvider = SSOFactory::createProvider('google');
        return $ssoProvider->redirectToProvider($request);
    }

    public function handleProviderCallback()
    {
        $ssoProvider = SSOFactory::createProvider('google');
        return $ssoProvider->handleProviderCallback();
    }

}
