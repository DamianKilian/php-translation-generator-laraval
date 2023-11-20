<?php

use PhpTranslationManagerLaravel\AssetController;
use PhpTranslationManagerLaravel\PhpTranslationManagerController;

Route::prefix('phptranslationmanager')->group(function () {
    Route::get('/', [PhpTranslationManagerController::class, 'phptranslationmanager']);
    Route::get('/js', [AssetController::class, 'js'])->name('js');
    Route::get('/css', [AssetController::class, 'css'])->name('css');
});
