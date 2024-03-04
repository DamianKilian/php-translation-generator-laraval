<!DOCTYPE html>
<html>

<head>
    <title>php-translation-manager-laravel</title>
    <link rel="stylesheet" href="{{ route('css') }}">
</head>

<body>
    <div id="app">
        <php-translation-manager save-trans-files-url="{{ route('save-trans-files') }}" search-url="{{ route('search') }}"
            get-trans-files-contents-data-url="{{ route('get-trans-files-contents-data') }}"></php-translation-manager>
    </div>
    <script src="{{ route('js') }}"></script>
</body>

</html>
