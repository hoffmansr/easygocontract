<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


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
         require_once app_path('Helpers/ContractHelper.php');
         View::composer('*', function ($view) {
        if (Auth::check()) {
            $user = Auth::user();
            $societeId = $user->societe_id;

            $notifications = $user->notifications()
                ->whereJsonContains('data->societe_id', $societeId)
                ->get();

            $newNotifications = $user->unreadNotifications()
                ->whereJsonContains('data->societe_id', $societeId)
                ->get();

            $view->with(compact('notifications', 'newNotifications'));
        } else {
            // Fournir des collections vides pour éviter les undefined
            $view->with(['notifications' => collect(), 'newNotifications' => collect()]);
        }
    });
    }
}
