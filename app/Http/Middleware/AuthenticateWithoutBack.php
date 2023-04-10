<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;

class AuthenticateWithoutBack extends Authenticate
{
    protected function unauthenticated($request, array $guards)
    {
        
    }
}
