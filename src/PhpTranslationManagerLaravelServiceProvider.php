<?php

namespace PhpTranslationManagerLaravel;

use Illuminate\Support\ServiceProvider;
use PhpTranslationManager\PhpTranslationManager;

class PhpTranslationManagerLaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/phptranslationmanager.php' => config_path('phptranslationmanager.php'),
        ], 'phptranslationmanager-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/phptranslationmanager.php',
            'phptranslationmanager'
        );
    }
}
