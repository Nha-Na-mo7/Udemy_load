<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

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
    public function boot(UrlGenerator $url)
    {
        // 開発環境の時、SQLをログに表示する
        if (config('app.env') !== 'production') {
          \DB::listen(function ($query) {
            \Log::info("Query Time:{$query->time}s] $query->sql");
          });
        }
        
        // 本番環境の時、URLをhttpsにする
        if (config('app.env') === 'production') {
          $url->forceScheme('https');
        }
    }
}
