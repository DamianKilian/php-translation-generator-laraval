<?php

use PhpTranslationManagerLaravel\AssetController;
use PhpTranslationManagerLaravel\PhpTranslationManagerController;

Route::prefix('phptranslationmanager')->group(function () {
    Route::post('/get-trans-files-contents-data', [PhpTranslationManagerController::class, 'getTransFilesContentsData'])->name('get-trans-files-contents-data');
    Route::post('/save-trans-files', [PhpTranslationManagerController::class, 'saveTransFiles'])->name('save-trans-files');
    Route::post('/search', [PhpTranslationManagerController::class, 'search'])->name('search');
    Route::get('/', [PhpTranslationManagerController::class, 'phptranslationmanager']);
    Route::get('/js', [AssetController::class, 'js'])->name('js');
    Route::get('/css', [AssetController::class, 'css'])->name('css');
    Route::get('/ttt', [PhpTranslationManagerLaravel\TttController::class, 'ttt'])->name('ttt');
});
