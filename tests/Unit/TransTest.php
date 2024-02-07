<?php

namespace PhpTranslationManagerLaravel\Tests\Unit;

use PhpTranslationManagerLaravel\Tests\TestCase;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;
use org\bovigo\vfs\vfsStream;

class TransTest extends TestCase
{

    public function test_saveTransFiles()
    {
        $transFilesContents = array(
            'en.json' => array(
                array('key' => 'k1', 'val' => 'v1',),
                array('key' => 'k2', 'val' => 'v2',),
                array('key' => 'k3', 'val' => 'v3',),
            ),
            'pl.json' => array(
                array('key' => 'k1', 'val' => 'v1',),
                array('key' => 'k2', 'val' => 'v2',),
                array('key' => 'k3', 'val' => 'v3',),
            ),
        );
        $root = vfsStream::setup('exampleDir');
        $phpTranslationManagerService = new PhpTranslationManagerService(vfsStream::url('exampleDir'));

        $this->assertTrue($transFilesContents == $phpTranslationManagerService->saveTransFiles($transFilesContents));
        $this->assertTrue($transFilesContents === $phpTranslationManagerService->getTransFilesContents());
    }

    public function test_streamSafeGlob()
    {
        $plJson = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];
        $enJson = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];
        $found = [
            "vfs://exampleDir/en.json",
            "vfs://exampleDir/pl.json",
        ];
        $root = vfsStream::setup('exampleDir');
        file_put_contents(vfsStream::url('exampleDir') . '/pl.json', json_encode($plJson));
        file_put_contents(vfsStream::url('exampleDir') . '/en.json', json_encode($enJson));
        $phpTranslationManagerService = new PhpTranslationManagerService(vfsStream::url('exampleDir'));

        $this->assertTrue($found === $this->invokeMethod($phpTranslationManagerService, 'streamSafeGlob', array(vfsStream::url('exampleDir'), '*.json')));
    }

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

    public function test_retrieveContent()
    {
        $phpTranslationManagerService = new PhpTranslationManagerService('');
        $testArrWrapped = [
            ['key' => 'k1', 'val' => 'v1'],
            ['key' => 'k2', 'val' => 'v2'],
            ['key' => 'k3', 'val' => 'v3'],
        ];
        $testArr = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];

        $testArrService = $this->invokeMethod($phpTranslationManagerService, 'retrieveContent', array($testArrWrapped));

        $this->assertTrue($testArr === $testArrService);
    }

    public function test_retrieveContent_duplicate_key_exception()
    {
        $phpTranslationManagerService = new PhpTranslationManagerService('');
        $testArrWrapped = [
            ['key' => 'k1', 'val' => 'v1'],
            ['key' => 'k2', 'val' => 'v2'],
            ['key' => 'k2', 'val' => 'v2'],
            ['key' => 'k3', 'val' => 'v3'],
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Duplicate key');

        $this->invokeMethod($phpTranslationManagerService, 'retrieveContent', array($testArrWrapped));
    }

    public function test_getTransFilesContents()
    {
        $plJson = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];
        $enJson = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];
        $root = vfsStream::setup('exampleDir');
        file_put_contents(vfsStream::url('exampleDir') . '/pl.json', json_encode($plJson));
        file_put_contents(vfsStream::url('exampleDir') . '/en.json', json_encode($enJson));
        $phpTranslationManagerService = new PhpTranslationManagerService(vfsStream::url('exampleDir'));
        $transFilesContents = array(
            'en.json' => array(
                array('key' => 'k1', 'val' => 'v1',),
                array('key' => 'k2', 'val' => 'v2',),
                array('key' => 'k3', 'val' => 'v3',),
            ),
            'pl.json' => array(
                array('key' => 'k1', 'val' => 'v1',),
                array('key' => 'k2', 'val' => 'v2',),
                array('key' => 'k3', 'val' => 'v3',),
            ),
        );

        $this->assertTrue($transFilesContents === $phpTranslationManagerService->getTransFilesContents());
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
