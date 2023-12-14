<?php

namespace PhpTranslationManagerLaravel;

use Illuminate\Support\ServiceProvider;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;
use Illuminate\Contracts\Foundation\Application;

class PhpTranslationManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/phptranslationmanagerlaravel.php' => config_path('phptranslationmanagerlaravel.php'),
        ], 'phptranslationmanagerlaravel-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'php-translation-manager-laravel');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/phptranslationmanagerlaravel.php',
            'phptranslationmanagerlaravel'
        );

        $this->app->singleton(PhpTranslationManagerService::class, function () {
            return new PhpTranslationManagerService(config('phptranslationmanagerlaravel.lang_path'));
        });
    }
}
