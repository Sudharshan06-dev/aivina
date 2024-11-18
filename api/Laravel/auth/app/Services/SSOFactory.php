<?php

namespace App\Services;

use App\Interfaces\SSOProviderInterface;
use Mockery\Exception;

class SSOFactory
{

    // Static method to access the singleton instance
    public static function createProvider($provider): SSOProviderInterface {
        return match ($provider) {
            'google' => new GoogleSSOAdapter(),
            default => throw new Exception("Unsupported provider"),
        };
    }

}
