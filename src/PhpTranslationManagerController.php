<?php

namespace PhpTranslationManagerLaravel;

use App\Http\Controllers\Controller;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;
class PhpTranslationManagerController extends Controller
{
    public function phptranslationmanager()
    {
        $transFilesContents = PhpTranslationManagerService::getTransFilesContents(config('phptranslationmanagerlaravel.lang_path'));
        return view('php-translation-manager-laravel::dashboard', [
            'transFilesContents' => $transFilesContents,
        ]);
    }

}
