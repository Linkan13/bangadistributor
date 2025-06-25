<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            app()->setLocale(auth()->user()->lang_code);
        } elseif (session()->has('locale')) {
            $lang = \Modules\Language\Entities\Language::where('code', session()->get('locale'))->first();
            if ($lang) {
                app()->setLocale(session()->get('locale'));
            } else {
                session()->forget('locale');
            }
        } elseif (app()->bound('general_setting')) {
            app()->setLocale(app('general_setting')->language_code);
        } else {
            app()->setLocale('en');
        }

        if (!empty($request->get('lang'))) {
            app()->setLocale($request->get('lang'));
        }

        return $next($request);
    }
}
