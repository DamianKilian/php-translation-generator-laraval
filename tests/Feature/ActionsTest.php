<?php

namespace PhpTranslationManagerLaravel\Tests\Feature;

use PhpTranslationManagerLaravel\Tests\TestCase;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;
use Mockery\MockInterface;

class ActionsTest extends TestCase
{
    public function test_main_page_opens(): void
    {
        $response = $this->get('phptranslationmanager');
        $response->assertStatus(200);
    }

    public function test_getTransFilesContentsData(): void
    {
        $this->mock(PhpTranslationManagerService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getTransFilesContents')->once();
        });

        $response = $this->postJson('phptranslationmanager/get-trans-files-contents-data');

        $response->assertStatus(200);
    }

    public function test_saveTransFiles(): void
    {
        $this->mock(PhpTranslationManagerService::class, function (MockInterface $mock) {
            $mock->shouldReceive('saveTransFiles')->once();
        });

        $response = $this->postJson('phptranslationmanager/save-trans-files');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_throw_exception_saveTransFiles(): void
    {
        $this->mock(PhpTranslationManagerService::class, function (MockInterface $mock) {
            $mock->shouldReceive('saveTransFiles')->once()->andThrowExceptions([new \Exception()]);
        });

        $response = $this->postJson('phptranslationmanager/save-trans-files');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
            ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \PhpTranslationManagerLaravel\PhpTranslationManagerServiceProvider::class,
        ];
    }
}
