var gulp = require('gulp'),
	plugins = require("gulp-load-plugins")({
		pattern: ['gulp-*', 'gulp.*', 'main-bower-files', 'jshint-stylish', 'del'],
		scope: ['devDependencies'],
		replaceString: /^gulp(-|\.)/,
		camelize: true,
		lazy: true
	}),
	supportedBrowsers = ["last 1 version", "> 1%", "ie 8"],
	sassOptions = {
		errLogToConsole: true,
		outputStyle: 'expanded'
	},
	sourceMapsDir = './',
	paths = {
		'dev': {
			'scripts': 'develop/',
			'vendor': 'bower_components/'
		},
		'prod': {
			'scripts': 'styles/all/theme/assets/',
			'vendor': 'styles/all/theme/vendor/'
		}
	};

// Bower
gulp.task('bower', function() {
	return plugins.bower()
		.pipe(gulp.dest(paths.dev.vendor));
});

// JS
gulp.task('js', function() {
	return gulp.src(paths.dev.scripts + '**/*.js')
		.pipe(plugins.sourcemaps.init())
			.pipe(plugins.jscs())
			.pipe(plugins.jshint())
			.pipe(plugins.jshint.reporter(plugins.jshintStylish))
			.pipe(plugins.rename({ suffix: '.min' }))
			.pipe(plugins.uglify())
		.pipe(plugins.sourcemaps.write(sourceMapsDir))
		.pipe(gulp.dest(paths.prod.scripts));
});

// SASS
gulp.task('sass', function() {
	gulp.src(paths.dev.scripts + '**/*.scss')
		.pipe(plugins.sourcemaps.init())
			.pipe(plugins.sass(sassOptions).on('error', plugins.sass.logError))
			.pipe(plugins.csscomb())
			.pipe(plugins.csslint({
				'adjoining-classes': false,
				'box-sizing': false,
				'regex-selectors': false,
				'unqualified-attributes': false
			}))
			.pipe(plugins.csslint.reporter())
			.pipe(plugins.autoprefixer(supportedBrowsers))
			.pipe(plugins.rename({ suffix: '.min' }))
			.pipe(plugins.minifyCss())
		.pipe(plugins.sourcemaps.write(sourceMapsDir))
		.pipe(gulp.dest(paths.prod.scripts));
});

// Vendor
gulp.task('vendor', function() {
	var mainFiles = plugins.mainBowerFiles();

	if (!mainFiles.length) {
		return;
	}

	var jsFilter = plugins.filter(['**/*.js', '!**/*.min.js']);
	var cssFilter = plugins.filter(['**/*.css', '!**/*.min.css']);

	return gulp.src(mainFiles, {base: paths.dev.vendor })
		.pipe(jsFilter)
		.pipe(plugins.rename({ suffix: '.min' }))
		.pipe(plugins.uglify())
		.pipe(gulp.dest(paths.prod.vendor))
		.pipe(jsFilter.restore())

		.pipe(cssFilter)
		.pipe(plugins.rename({ suffix: '.min' }))
		.pipe(plugins.minifyCss())
		.pipe(gulp.dest(paths.prod.vendor))
		.pipe(cssFilter.restore())
		.pipe(gulp.dest(paths.prod.vendor));
});

// Clean up
gulp.task('clean', function(cb) {
	plugins.del([
		paths.prod.scripts + '**/theme/assets/',
		paths.prod.vendor
	], cb);
});

gulp.task('watch', function() {
	// Watch js files
	gulp.watch(paths.dev.scripts + '**/*.js', ['js']);

	// Watch sass files
	gulp.watch(paths.dev.scripts + '**/*.scss', ['sass']);

	// Watch Vendor files
	gulp.watch(paths.dev.vendor + '**', ['vendor']);

	// Watch bower.json
	gulp.watch('./bower.json', ['bower']);
});

gulp.task('build', ['clean'], function() {
	gulp.start('js', 'sass', 'vendor');
});