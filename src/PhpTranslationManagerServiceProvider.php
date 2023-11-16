<?php

namespace PhpTranslationManagerLaravel;

use Illuminate\Support\ServiceProvider;
use PhpTranslationManager\PhpTranslationManager;

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
    }
}
