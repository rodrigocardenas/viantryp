<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\User;
use App\Observers\UserObserver;

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
        if (str_starts_with(config('app.url'), 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        User::observe(UserObserver::class);

        // Configurar Carbon para usar español
        Carbon::setLocale('es');
        setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'Spanish');

        // Cargar helpers
        require_once app_path('Helpers/AirportHelper.php');

        // Cargar datos de aeropuertos para lookup de países
        $selectorsPath = resource_path('js/data/selectors.json');
        if (file_exists($selectorsPath)) {
            $selectorsData = json_decode(file_get_contents($selectorsPath), true);
            View::share('airportSelectors', $selectorsData['airports'] ?? []);
        }
    }
}
