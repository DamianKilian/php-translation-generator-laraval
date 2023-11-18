
let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'assets/js')
    .vue({ version: 3 })
    .sass('resources/sass/app.scss', 'assets/css');
