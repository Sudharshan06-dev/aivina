<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface  SSOProviderInterface
{
    public function redirectToProvider(Request $request);
    public function handleProviderCallback();
}
