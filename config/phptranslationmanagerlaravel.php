<?php

/*
|
| regex for finding translations e.g. __('trans1'), __("trans1")
|
*/
$regex = <<<'END'
/__\(\s*'\s*([^']*(\\')*[^']*)*\s*'\s*\)|__\(\s*"\s*([^"]*(\\")*[^"]*)*\s*"\s*\)/
END;

/*
|
| regex for triminng e.g. __('trans1') to trans1
|
*/
$trimRegex = <<<'END'
/^__\(\s*'\s*|^__\(\s*"\s*|\s*'\s*\)$|\s*"\s*\)$/
END;

return [
    'translate_from' => 'en',
    'translation_api' => 'google',

    /*
    |
    | Location of language files (currently .json are supported only)
    |
    */
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
