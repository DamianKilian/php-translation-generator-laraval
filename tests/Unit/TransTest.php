<?php

namespace PhpTranslationManagerLaravel\Tests\Unit;

use PhpTranslationManagerLaravel\Tests\TestCase;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;

class TransTest extends TestCase
{

    public function test_wrapElementsInArray()
    {
        $phpTranslationManagerService = new PhpTranslationManagerService('tt');
        $testArr = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];
        $testArrWrapped = $phpTranslationManagerService->wrapElementsInArray($testArr);
        $testArrUnwrapped = [];
        foreach ($testArrWrapped as $value) {
            $testArrUnwrapped[$value['key']] = $value['val'];
        }
        $this->assertTrue($testArrUnwrapped === $testArr);
    }
}
