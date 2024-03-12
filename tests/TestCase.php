<?php

namespace PhpTranslationManagerLaravel\Tests;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['PhpTranslationManagerLaravel_testing'] = true;
        parent::setUp();
    }
}
