<?php

namespace PhpTranslationManagerLaravel;

use Illuminate\Support\ServiceProvider;
use PhpTranslationManager\PhpTranslationManager;

class PhpTranslationManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/phptranslationmanager.php' => config_path('phptranslationmanager.php'),
        ], 'phptranslationmanager-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'phptranslationmanager');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/phptranslationmanager.php',
            'phptranslationmanager'
        );
    }
}
