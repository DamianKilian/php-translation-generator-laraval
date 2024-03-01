<?php

$regex = <<<'END'
/__\(\s*'\s*([^']*(\\')*[^']*)*\s*'\s*\)|__\(\s*"\s*([^"]*(\\")*[^"]*)*\s*"\s*\)/
END;

return [
    'lang_path' => 'resources/lang',
    'search_locations' => [
        '/resources/views' => [
            'regex' => $regex,
            'path' => 'resources/views',
            'file_extensions' => ['blade.php'],
        ],
        '/resources/js' => [
            'regex' => $regex,
            'path' => 'resources/js',
            'file_extensions' => ['vue'],
        ],
    ]
];
