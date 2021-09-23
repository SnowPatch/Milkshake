let mix = require('laravel-mix');

mix.sass('./assets/sass/app.scss', './public/css')
mix.js('./assets/js/app.js', './public/js')