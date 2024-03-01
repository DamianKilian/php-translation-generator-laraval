<?php

namespace PhpTranslationManagerLaravel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;

class PhpTranslationManagerController extends Controller
{
    public function getTransFilesContentsData(PhpTranslationManagerService $phpTranslationManagerService)
    {
        return response()->json([
            'transFilesContents' => $phpTranslationManagerService->getTransFilesContents(),
        ]);
    }

    public function phptranslationmanager(PhpTranslationManagerService $phpTranslationManagerService)
    {
        return view('php-translation-manager-laravel::dashboard');
    }

    public function saveTransFiles(Request $request, PhpTranslationManagerService $phpTranslationManagerService)
    {
        try {
            $newTransFilesContents = $phpTranslationManagerService->saveTransFiles($request->trans);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ]);
        }
        return response()->json([
            'success' => true,
            'transFilesContents' => $newTransFilesContents,
        ]);
    }

    public function search(Request $request, PhpTranslationManagerService $phpTranslationManagerService)
    {
        return response()->json([
            'searchResults' => $phpTranslationManagerService->search($request->langCode),
        ]);
    }
}
