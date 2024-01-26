<?php

namespace App\Providers;

use App\WebSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot() {
        View::composer('admin.includes.sidebar', function($view) {
            $user = auth()->user();
            $view->with('user', $user);
        });

        View::composer('layouts.admin', function($view) {
            $web = WebSetting::where('web_status',1)->where('id', 1)->firstOrFail();
            $view->with('web', $web);
        });

        View::composer('admin.includes.topbar', 'App\Http\View\ViewComposers\StockComposer');
    }
}
