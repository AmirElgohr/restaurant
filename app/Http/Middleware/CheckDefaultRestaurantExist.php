<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDefaultRestaurantExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

//        if (empty($request->user()->restaurant)) {
//            auth()->logout();
//            return redirect(route('login'))->withErrors(['email' => __('auth.default_restaurant_not_exist')]);
//        }
        return $next($request);
    }
}
