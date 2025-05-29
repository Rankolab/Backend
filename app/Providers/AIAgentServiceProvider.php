<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AIAgent\AIAgentService;

class AIAgentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AIAgentService::class, function ($app) {
            return new AIAgentService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
