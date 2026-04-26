<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;

class PreventSessionFixation
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('post') && $request->route() && 
            in_array($request->route()->getName(), ['login', 'register'])) {
            $request->session()->migrate(true);
        }
        
        return $next($request);
    }
}