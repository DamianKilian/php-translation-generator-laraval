<?php

$regex = <<<'END'
/__\(\s*'\s*([^']*(\\')*[^']*)*\s*'\s*\)|__\(\s*"\s*([^"]*(\\")*[^"]*)*\s*"\s*\)/
END;

$trimRegex = <<<'END'
/^__\(\s*'\s*|^__\(\s*"\s*|\s*'\s*\)$|\s*"\s*\)$/
END;

$ferp = function_exists('resource_path');

return [
    'lang_path' => $ferp ? resource_path() . '/lang' : null,
    'search_locations' => [
        '/resources/views' => [
            'regex' => $regex,
            'trimRegex' => $trimRegex,
            'path' => $ferp ? resource_path() . '/views' : null,
            'file_extensions' => ['blade.php'],
        ],
        '/resources/js' => [
            'regex' => $regex,
            'trimRegex' => $trimRegex,
            'path' => $ferp ? resource_path() . '/js' : null,
            'file_extensions' => ['vue'],
        ],
    ]
];
