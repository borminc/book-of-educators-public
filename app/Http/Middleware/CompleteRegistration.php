<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\RegistrationController;

class CompleteRegistration
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
        $user = request()->user();

        if (!$user->roles()->exists()) {
            return redirect()->route('registration.select-roles-view');
        }

        if ($user->hasRole(Role::SCHOOL)) {
            if (!$user->school()->exists()) {
                return redirect()->route('registration.add-school-view');
            }
        }

        if ($user->hasRole(Role::INSTRUCTOR)) {
            if (!$user->instructorLevels()->exists()) {
                return redirect()->route('registration.add-instructor-level-view');
            }

            if (!$user->subjects()->exists()) {
                return redirect()->route('registration.add-subject-view');
            }
        }

        if (!$user->contact()->exists()) {
            return redirect()->route('registration.add-contact-view');
        }

        return $next($request);
    }
}
