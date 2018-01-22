var elixir = require('laravel-elixir');

require('laravel-elixir-svgstore');
require('laravel-elixir-webpack-official');
require('laravel-elixir-livereload');

var svgminPlugins = [
	{ collapseGroups: false },
	{ removeUnknownsAndDefaults: false },
	{ removeUselessStrokeAndFill: false },
];

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	mix.sass('app.sass')
		.babel('app.js', 'public/js/app.js')
		.svgstore('resources/assets/svg', 'public/img/', 'sprite.svg', svgminPlugins)
		.livereload();
});
