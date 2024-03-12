<?php

namespace PhpTranslationManagerLaravel\Tests\Feature;

use PhpTranslationManagerLaravel\Tests\TestCase;

class ActionsTest extends TestCase
{
    public function test_main_page_opens(): void
    {
        $response = $this->get('phptranslationmanager');
        $response->assertStatus(200);
    }

    protected function getPackageProviders($app)
    {
        return [
            \PhpTranslationManagerLaravel\PhpTranslationManagerServiceProvider::class,
        ];
    }
}
