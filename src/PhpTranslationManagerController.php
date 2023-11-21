<?php

namespace PhpTranslationManagerLaravel;

use App\Http\Controllers\Controller;
use PhpTranslationManager\PhpTranslationManager;

class PhpTranslationManagerController extends Controller
{
    public function phptranslationmanager()
    {
        $transFilesContents = PhpTranslationManager::getTransFilesContents(config('phptranslationmanagerlaravel.lang_path'));
        return view('php-translation-manager-laravel::dashboard', [
            'transFilesContents' => $transFilesContents,
        ]);
    }

}
