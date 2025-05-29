<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Define gates for role-based permissions
        Gate::define('admin', function (User $user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });

        Gate::define('super_admin', function (User $user) {
            return $user->role === 'super_admin';
        });

        Gate::define('manage_users', function (User $user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });

        Gate::define('manage_content', function (User $user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });

        Gate::define('manage_settings', function (User $user) {
            return $user->role === 'super_admin';
        });

        Gate::define('view_reports', function (User $user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });

        Gate::define('manage_api', function (User $user) {
            return $user->role === 'super_admin';
        });

        // Add custom Blade directives for roles
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });

        Blade::if('admin', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']);
        });

        Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->role === 'super_admin';
        });
    }
}
