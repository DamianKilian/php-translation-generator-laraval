<?php

$regex = <<<'END'
/__\(\s*'\s*([^']*(\\')*[^']*)*\s*'\s*\)|__\(\s*"\s*([^"]*(\\")*[^"]*)*\s*"\s*\)/
END;

$trimRegex = <<<'END'
/^__\(\s*'\s*|^__\(\s*"\s*|\s*'\s*\)$|\s*"\s*\)$/
END;

return [
    'translate_from' => 'en',
    'translation_api' => 'google',
    'lang_path' => resource_path() . '/lang',
    'search_locations' => [
        '/resources/views' => [
            'regex' => $regex,
            'trimRegex' => $trimRegex,
            'path' => resource_path() . '/views',
            'file_extensions' => ['blade.php'],
        ],
        '/resources/js' => [
            'regex' => $regex,
            'trimRegex' => $trimRegex,
            'path' => resource_path() . '/js',
            'file_extensions' => ['vue'],
        ],
    ]
];
