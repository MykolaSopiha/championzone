var elixir = require('laravel-elixir');

require('laravel-elixir-svgstore');
require('laravel-elixir-webpack');
require('laravel-elixir-livereload');

	{ collapseGroups: false }
var svgminPlugins = [
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
		.styles([
			'libs/jquery-ui.css',
			'libs/inputmask.min.css'
		])
		.scripts([
			'libs/jquery-3.2.1.min.js',
			'libs/jquery-ui.js',
			'libs/jquery.dataTables.min.js',
			'libs/datepicker-ru.js',
			'app.js'
		], 'public/js/app.js')
		.svgstore('resources/assets/svg', 'public/img/', 'sprite.svg', svgminPlugins)
		.livereload();
});
