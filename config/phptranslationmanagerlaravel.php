<?php

$regex = <<<'END'
/__\(\s*'\s*([^']*(\\')*[^']*)*\s*'\s*\)|__\(\s*"\s*([^"]*(\\")*[^"]*)*\s*"\s*\)/
END;

$trimRegex = <<<'END'
/^__\(\s*'\s*|^__\(\s*"\s*|\s*'\s*\)$|\s*"\s*\)$/
END;

$testing = isset($GLOBALS['PhpTranslationManagerLaravel_testing']);

return [
    'translate_from' => 'en',
    'translation_api' => 'google',
    'lang_path' => !$testing ? resource_path() . '/lang' : null,
    'search_locations' => [
        '/resources/views' => [
            'regex' => $regex,
            'trimRegex' => $trimRegex,
            'path' => !$testing ? resource_path() . '/views' : null,
            'file_extensions' => ['blade.php'],
        ],
        '/resources/js' => [
            'regex' => $regex,
            'trimRegex' => $trimRegex,
            'path' => !$testing ? resource_path() . '/js' : null,
            'file_extensions' => ['vue'],
        ],
    ]
];
