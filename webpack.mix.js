const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let productionSourceMaps = false;

mix.js('resources/js/app.js', 'public/theme/js')
    .sourceMaps(productionSourceMaps, 'source-map')
    .vue()
    .sass('resources/sass/app.scss', 'public/theme/css');

mix.webpackConfig({
    resolve: {
        fallback: {
            "crypto": false 
        }
    } 
});


