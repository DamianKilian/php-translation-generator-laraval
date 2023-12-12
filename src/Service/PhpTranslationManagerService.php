<?php

namespace PhpTranslationManagerLaravel\Service;

class PhpTranslationManagerService
{
    public static function getTransFilesContents($transPath)
    {
        $transFiles = glob($transPath . '/*.json');
        $transFilesContents = [];
        foreach ($transFiles as $transFile) {
            $langCode = str_replace($transPath . '/', '', $transFile);
            $json = file_get_contents($transFile);
            $transFilesContents[$langCode] = json_decode($json, true);
        }
        return $transFilesContents;
    }
}
