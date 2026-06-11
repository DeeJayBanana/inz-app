<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        URL::forceScheme('https');
        
        Gate::before(function ($user, $ability) {
            return $user->hasRole('administrator') ? true : null;
        });

       View::composer('*', function ($view) {

          if(Auth::check()) {
              $view->with('user', Auth::user());
          }

       });

       View::share('alertTypes', [
          'success' => 'success',
           'error' => 'danger',
           'warning' => 'warning',
           'info' => 'info',
           'message' => 'info'
       ]);
    }
}
