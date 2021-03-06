<?php

namespace App\Http\Middleware;

use Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $local=app()->getLocale();
        if (! $request->expectsJson()) {
            if(Request::is($local.'/admin*')){
                return route('admin.login');
            }else{
                return route('logi00n');
            }
        }
    }
}
