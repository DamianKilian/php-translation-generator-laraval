<!DOCTYPE html>
<html>

<head>
    <title>php-translation-manager-laravel</title>
    <link rel="stylesheet" href="{{ route('css') }}">
</head>

<body>
    <div id="app">
        <php-translation-manager save-trans-files-url="{{ route('save-trans-files') }}"
            :trans-files-contents-prop='@json($transFilesContents)'></php-translation-manager>
    </div>
    <script src="{{ route('js') }}"></script>
</body>

</html>
