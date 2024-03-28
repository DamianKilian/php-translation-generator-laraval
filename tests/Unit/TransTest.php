<?php

namespace PhpTranslationManagerLaravel\Tests\Unit;

use PhpTranslationManagerLaravel\Tests\TestCase;
use PhpTranslationManagerLaravel\Service\PhpTranslationManagerService;
use org\bovigo\vfs\vfsStream;

class TransTest extends TestCase
{
    public function test_translate()
    {
        $gt = $this->createStub(\Stichoza\GoogleTranslate\GoogleTranslate::class);
        $gt->method('translate')
            ->willReturn('przykładowy tekst');
        $phpTranslationManagerService = new PhpTranslationManagerService();

        $this->assertTrue(['pl.json' => 'przykładowy tekst'] === $phpTranslationManagerService->translate($gt, 'example text', 'pl', 'google', 'en'));
    }

    public function test_search()
    {
        $enJson = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
            'kUnused' => 'vUnused',
        ];
        $fileContent1 = <<<'EOD'
        foo bar __('toFind1') foo bar
        foo bar
        __(  '  to\'\'Find2  '  ) foo bar
        foo bar
        __('k1') foo bar
        foo bar
        __('k3') foo bar
        foo bar
        EOD;
        $fileContent2 = <<<'EOD'
        foo bar __('toFind3') foo bar
        foo bar
        __(  '  to\'\'Find4  '  ) foo bar
        foo bar
        __('k1') foo bar
        foo bar
        __('k3') foo bar
        foo bar
        EOD;
        $structure = [
            'en.json' => json_encode($enJson),
            'resources' => [
                'views' => [
                    'file1.blade.php' => $fileContent1,
                    'dir1' => [
                        'file2.blade.php' => $fileContent2,
                    ],
                ],
            ],
        ];
        vfsStream::setup('exampleDir', null, $structure);
        $config = (require(__DIR__ . '/../../config/phptranslationmanagerlaravel.php'))['search_locations'];
        unset($config['/resources/js']);
        $config['/resources/views']['path'] = vfsStream::url('exampleDir') . '/resources/views';
        $phpTranslationManagerService = new PhpTranslationManagerService(vfsStream::url('exampleDir'), $config);

        $resultsService = $phpTranslationManagerService->search('en.json');
        $results = [
            'en.json' => [
                'foundTrans' => [
                    '/resources/views' => [
                        'toFind1',
                        "to\'\'Find2",
                        'toFind3',
                        "to\'\'Find4",
                    ]
                ],
                'unused' => [
                    "k2" => "v2",
                    'kUnused' => 'vUnused',
                ],
            ]
        ];

        $this->assertTrue([] === array_diff(
            $resultsService['en.json']['foundTrans']['/resources/views'],
            $results['en.json']['foundTrans']['/resources/views']
        ));
        $this->assertTrue([] === array_diff(
            $resultsService['en.json']['unused'],
            $results['en.json']['unused']
        ));
    }

    public function test_saveTransFiles()
    {
        $transFilesContents = array(
            'en.json' => array(
                ['key' => 'k1', 'val' => 'v1',],
                ['key' => 'k2', 'val' => 'v2',],
                ['key' => 'k3', 'val' => 'v3',],
            ),
            'pl.json' => array(
                ['key' => 'k1', 'val' => 'v1',],
                ['key' => 'k2', 'val' => 'v2',],
                ['key' => 'k3', 'val' => 'v3',],
            ),
        );
        $transFilesContentsWithMeta = array(
            'en.json' => array(
                ['key' => 'k1', 'val' => 'v1', 'meta' => ['deleted' => false],],
                ['key' => 'k2', 'val' => 'v2', 'meta' => ['deleted' => false],],
                ['key' => 'k3', 'val' => 'v3', 'meta' => ['deleted' => false],],
            ),
            'pl.json' => array(
                ['key' => 'k1', 'val' => 'v1', 'meta' => ['deleted' => false],],
                ['key' => 'k2', 'val' => 'v2', 'meta' => ['deleted' => false],],
                ['key' => 'k3', 'val' => 'v3', 'meta' => ['deleted' => false],],
            ),
        );
        $root = vfsStream::setup('exampleDir');
        $phpTranslationManagerService = new PhpTranslationManagerService(vfsStream::url('exampleDir'));

        $this->assertTrue($transFilesContents == $phpTranslationManagerService->saveTransFiles($transFilesContentsWithMeta));
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
            ['key' => 'kdel1', 'val' => 'vdel1', 'meta' => ['deleted' => true],],
            ['key' => 'k1', 'val' => 'v1', 'meta' => ['deleted' => false],],
            ['key' => 'kdel2', 'val' => 'vdel2', 'meta' => ['deleted' => true],],
            ['key' => 'k2', 'val' => 'v2', 'meta' => ['deleted' => false],],
            ['key' => 'k3', 'val' => 'v3', 'meta' => ['deleted' => false],],
            ['key' => 'kdel2', 'val' => 'vdel2', 'meta' => ['deleted' => true],],
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
            ['key' => 'k1', 'val' => 'v1', 'meta' => ['deleted' => false],],
            ['key' => 'k2', 'val' => 'v2', 'meta' => ['deleted' => false],],
            ['key' => 'k2', 'val' => 'v2', 'meta' => ['deleted' => false],],
            ['key' => 'k3', 'val' => 'v3', 'meta' => ['deleted' => false],],
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

    public function test_lang_path_error_getTransFilesContents()
    {
        vfsStream::setup('exampleDir');

        $phpTranslationManagerService = new PhpTranslationManagerService(vfsStream::url('exampleDir') . '/notSetDir');

        $this->assertTrue(isset($phpTranslationManagerService->getTransFilesContents()['error']));
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
