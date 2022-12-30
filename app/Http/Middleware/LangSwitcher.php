<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;

class LangSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has('lang')){
            (in_array(request()->segment(1), ['en'])) ?  Session::put('lang', request()->segment(1)) :  Session::put('lang', 'fr');
        }
        App::setLocale(session()->get('lang'));
        return $next($request);
    }
}
