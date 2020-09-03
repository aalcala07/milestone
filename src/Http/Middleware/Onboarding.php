<?php

namespace Milestone\Http\Middleware;

use Closure;
use Milestone\UserSettings;

class Onboarding
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
        $settings = UserSettings::where('user_id', auth()->user()->id)->first();
        if (!$settings->onboarded) {
            return redirect()->route('onboarding.index');
        }
        return $next($request);
    }
}
