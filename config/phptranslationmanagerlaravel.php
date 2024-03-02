<?php

$regex = <<<'END'
/__\(\s*'\s*([^']*(\\')*[^']*)*\s*'\s*\)|__\(\s*"\s*([^"]*(\\")*[^"]*)*\s*"\s*\)/
END;

$trimRegex = <<<'END'
/^__\(\s*'\s*|^__\(\s*"\s*|\s*'\s*\)$|\s*"\s*\)$/
END;

return [
    'lang_path' => 'resources/lang',
    'search_locations' => [
        '/resources/views' => [
            'regex' => $regex,
            'trimRegex' => $trimRegex,
            'path' => 'resources/views',
            'file_extensions' => ['blade.php'],
        ],
        '/resources/js' => [
            'regex' => $regex,
            'trimRegex' => $trimRegex,
            'path' => 'resources/js',
            'file_extensions' => ['vue'],
        ],
    ]
];
