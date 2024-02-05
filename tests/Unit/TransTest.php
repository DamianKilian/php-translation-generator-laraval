<?php

namespace PhpTranslationManagerLaravel\Tests\Unit;

use PhpTranslationManagerLaravel\Tests\TestCase;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;

class TransTest extends TestCase
{

    public function test_wrapElementsInArray()
    {
        $phpTranslationManagerService = new PhpTranslationManagerService('');
        $testArr = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];
        $testArrWrapped = [
            ['key' => 'k1', 'val' => 'v1'],
            ['key' => 'k2', 'val' => 'v2'],
            ['key' => 'k3', 'val' => 'v3'],
        ];

        $testArrWrappedService = $phpTranslationManagerService->wrapElementsInArray($testArr);

        $this->assertTrue($testArrWrapped === $testArrWrappedService);
    }

}
