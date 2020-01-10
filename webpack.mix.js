let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.js('public/templates/wordify/js/main.js', 'public/js/main.js')
	.copy('public/templates/wordify/js/bootstrap.min.js', 'public/js/bootstrap.min.js')
	.copy('public/templates/wordify/js/jquery-3.2.1.min.js', 'public/js/jquery-3.2.1.min.js')
	.copy('public/templates/wordify/js/jquery-migrate-3.0.0.js', 'public/js/jquery-migrate-3.0.0.js')
	.copy('public/templates/wordify/js/jquery.stellar.min.js', 'public/js/jquery.stellar.min.js')
	.copy('public/templates/wordify/js/jquery.waypoints.min.js', 'public/js/jquery.waypoints.min.js')
	.copy('public/templates/wordify/js/owl.carousel.min.js', 'public/js/owl.carousel.min.js')
	.copy('public/templates/wordify/js/popper.min.js', 'public/js/popper.min.js')
	.scripts('public/templates/wordify/js/validatorFile.js', 'public/js/validatorFile.js')
	.copyDirectory('public/templates/wordify/images', 'public/images')
	.styles([
		'public/templates/wordify/css/style.css',
		'public/templates/wordify/css/animate.css',
		'public/templates/wordify/css/bootstrap.css',
		'public/templates/wordify/css/flaticon.css',
		'public/templates/wordify/css/owl.carousel.min.css',
		'public/templates/wordify/css/style.css',
		'public/templates/wordify/css/styleError.css',
		'public/templates/wordify/fonts/ionicons/css/ionicons.min.css',
		'public/templates/wordify/fonts/flaticon/font/flaticon.css'
		], 'public/css/app.css')
		.options({
	      processCssUrls: false
		   })
	.sass('public/templates/wordify/fonts/fontawesome/css/font-awesome.min.scss','public/fonts/fontawesome/css/font-awesome.min.css')
		.options({
			processCssUrls: false
		 })
	.copyDirectory('public/templates/wordify/fonts', 'public/fonts');