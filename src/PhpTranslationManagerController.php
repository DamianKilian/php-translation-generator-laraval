<?php

namespace PhpTranslationManagerLaravel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;

class PhpTranslationManagerController extends Controller
{
    public function phptranslationmanager(PhpTranslationManagerService $phpTranslationManagerService)
    {
        $transFilesContents = $phpTranslationManagerService->getTransFilesContents();
        return view('php-translation-manager-laravel::dashboard', [
            'transFilesContents' => $transFilesContents,
        ]);
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
}
