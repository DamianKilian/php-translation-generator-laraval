<?php

$regex = <<<'END'
/__\(\s*'\s*([^']*(\\')*[^']*)*\s*'\s*\)|__\(\s*"\s*([^"]*(\\")*[^"]*)*\s*"\s*\)/
END;

return [
    'lang_path' => resource_path() . '/lang',
    'search_locations' => [
        '/resources/views' => [
            'regex' => $regex,
            'path' => resource_path() . '/views',
            'file_extensions' => ['blade.php'],
        ],
        '/resources/js' => [
            'regex' => $regex,
            'path' => resource_path() . '/js',
            'file_extensions' => ['vue'],
        ],
    ]
];
