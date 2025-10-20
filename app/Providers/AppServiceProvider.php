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
            // Récupérer les notifications non lues
            $user = Auth::user();
            $notifications = $user->notifications()->latest()->take(10)->get();
            $unreadCount = $user->unreadNotifications()->count();
        } else {
            $notifications = collect();
            $unreadCount = 0;
        }

        $view->with([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    });
    }
}
