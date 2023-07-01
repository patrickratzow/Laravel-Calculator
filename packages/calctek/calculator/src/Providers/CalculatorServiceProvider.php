<?php

namespace CalcTek\Calculator\Providers;

use CalcTek\Calculator\Contracts\CalculatorService;
use CalcTek\Calculator\Services\CalcTekCalculatorService;
use Illuminate\Support\ServiceProvider;

class CalculatorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CalculatorService::class, CalcTekCalculatorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
