<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Seguridad de Eloquent ──
        // Prevenir lazy loading en desarrollo (detecta N+1)
        Model::preventLazyLoading(! $this->app->isProduction());

        // Prevenir asignación masiva silenciosa
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

        // ── Gate: admin tiene acceso total ──
        Gate::before(function ($user, $ability) {
            if ($user->is_admin) {
                return true;
            }
        });
    }
}
