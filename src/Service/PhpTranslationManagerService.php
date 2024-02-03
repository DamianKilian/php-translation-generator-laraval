<?php

namespace PhpTranslationManagerLaravel\Service;

class PhpTranslationManagerService
{
    const ERR_MSG = 'Duplicate key';
    protected $langPath;

    public function __construct($langPath)
    {
        $this->langPath = $langPath;
    }

    public function getTransFilesContents()
    {
        $transFiles = glob($this->langPath . '/*.json');
        $transFilesContents = [];
        foreach ($transFiles as $transFile) {
            $langCode = str_replace($this->langPath . '/', '', $transFile);
            $json = file_get_contents($transFile);
            $transFilesContents[$langCode] = $this->wrapElementsInArray(json_decode($json, true));
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
