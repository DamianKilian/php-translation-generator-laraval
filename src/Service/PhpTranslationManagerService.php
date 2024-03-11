<?php

namespace PhpTranslationManagerLaravel\Service;

use Stichoza\GoogleTranslate\GoogleTranslate;

class PhpTranslationManagerService
{
    const ERR_MSG = 'Duplicate key';
    protected $langPath;
    protected $searchLocations;

    public function __construct($langPath = '', $searchLocations = [])
    {
        $this->langPath = $langPath;
        $this->searchLocations = $searchLocations;
    }

    public function translate(GoogleTranslate $gt, $str, $code, $translationApi, $translateFrom)
    {
        if ('google' === $translationApi) {
            $gt->setSource($translateFrom);
            $gt->setTarget($code);
            $trans = $gt->translate($str);
            return ["$code.json" => $trans];
        }
    }

    public function search($langCode)
    {
        $transFilesContents = $this->getTransFilesContents(false, $langCode)[$langCode];
        $foundTrans = [];
        foreach ($this->searchLocations as $relativePath => $location) {
            foreach ($location['file_extensions'] as $ext) {
                $files = $this->streamSafeGlob($location['path'], '*.' . $ext, true);
                foreach ($files as $file) {
                    preg_match_all(
                        $location['regex'],
                        file_get_contents($file),
                        $matches,
                    );
                    foreach ($matches[0] as $match) {
                        $trans = preg_replace($location['trimRegex'], '', $match);
                        if (
                            !array_key_exists($trans, $transFilesContents) &&
                            (!array_key_exists($relativePath, $foundTrans) || false === array_search($trans, $foundTrans[$relativePath]))
                        ) {
                            $foundTrans[$relativePath][] = $trans;
                        }
                    }
                }
            }
        }
        return [$langCode => $foundTrans];
    }

    /**
     * Glob that is safe with streams (vfs for example)
     *
     * @param string $directory
     * @param string $filePattern
     * @return array
     */
    private function streamSafeGlob($directory, $filePattern, $recursive = false)
    {
        $files = scandir($directory);
        $found = array();
        foreach ($files as $filename) {
            if ($recursive && $filename !== '.' && $filename !== '..' && is_dir($directory . '/' . $filename)) {
                $found = array_merge($found, $this->streamSafeGlob($directory . '/' . $filename, $filePattern, true));
            }
            if (fnmatch($filePattern, $filename)) {
                $found[] = $directory . '/' . $filename;
            }
        }
        return $found;
    }

    public function getTransFilesContents($wrapped = true, $langCodeFilter = '')
    {
        $transFiles = $this->streamSafeGlob($this->langPath, '*.json');
        $transFilesContents = [];
        foreach ($transFiles as $transFile) {
            $langCode = str_replace($this->langPath . '/', '', $transFile);
            if ($langCodeFilter &&  $langCodeFilter !==  $langCode) {
                continue;
            }
            $json = file_get_contents($transFile);
            $transFilesContents[$langCode] = $wrapped ? $this->wrapElementsInArray(json_decode($json, true)) : json_decode($json, true);
        }
        return $transFilesContents;
    }

    public function wrapElementsInArray($arr)
    {
        $arrWrapped = [];
        foreach ($arr as $key => $val) {
            $arrWrapped[] = ['key' => $key, 'val' => $val];
        }
        return $arrWrapped;
    }

    public function saveTransFiles($transFilesContents)
    {
        $newTransFilesContents = [];
        foreach ($transFilesContents as $langCode => $trans) {
            $newTransFilesContents[$langCode] = $this->retrieveContent($trans);
        }
        $this->saveTransFilesContents($newTransFilesContents);
        foreach ($newTransFilesContents as $key => $arr) {
            $newTransFilesContents[$key] = $this->wrapElementsInArray($arr);
        }
        return $newTransFilesContents;
    }

    protected function retrieveContent($trans)
    {
        $newTransFilesContent = [];
        foreach ($trans as $value) {
            if ($value['meta']['deleted']) {
                continue;
            }
            if (isset($newTransFilesContent[$value['key']])) {
                throw new \Exception(self::ERR_MSG);
            }
            $newTransFilesContent[$value['key']] = $value['val'];
        }
        return $newTransFilesContent;
    }

    protected function saveTransFilesContents($newTransFilesContents)
    {
        foreach ($newTransFilesContents as $langCode => $newTrans) {
            file_put_contents($this->langPath . '/' . $langCode, json_encode($newTrans, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}
