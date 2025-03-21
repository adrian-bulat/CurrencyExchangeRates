<?php

namespace App\Providers;

use App\Interfaces\AuthInterface;
use App\Interfaces\ExchangeRateServiceInterface;
use App\Interfaces\ExchangeRateUpdaterInterface;
use App\Services\AuthService;
use App\Services\ExchangeRateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExchangeRateUpdaterInterface::class, ExchangeRateService::class);
        $this->app->bind(ExchangeRateServiceInterface::class, ExchangeRateService::class);
        $this->app->bind(AuthInterface::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard(); // Filament: Unguarding all models
    }
}
