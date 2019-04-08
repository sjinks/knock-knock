'use strict';

var gulp         = require('gulp');
var del          = require('del');
var exec         = require('gulp-exec');
var gulpif       = require('gulp-if');
var imagemin     = require('gulp-imagemin');
var prune        = require('gulp-prune');
var newer        = require('gulp-newer');
var rename       = require('gulp-rename');
var sourcemaps   = require('gulp-sourcemaps');
var postcss      = require('gulp-postcss');
var uglify       = require('gulp-uglify');
var rsync        = require('rsyncwrapper');
var config       = require('./.internal/config.json');

gulp.task('clean', function() {
	return del(['.tmp', 'public/**', '!public']);
});

gulp.task('clean:images', function() {
	return del(['public/img']);
});

gulp.task('clean:css', function() {
	return del(['.tmp/styles', 'public/css']);
});

gulp.task('clean:scripts', function() {
	return del(['.tmp/scripts', 'public/js']);
});

gulp.task('clean:files', function() {
	return del([
		'./public/*',
		'!./public/css',
		'!./public/img',
		'!./public/js'
	]);
});

gulp.task('images', function() {
	var src  = ['public-dev/img/**/*'];
	var dest = 'public/img';
	return gulp.src(src)
		.pipe(prune(dest))
		.pipe(newer(dest))
		.pipe(imagemin([
			imagemin.gifsicle({interlaced: true}),
			imagemin.jpegtran({progressive: true}),
			imagemin.optipng({optimizationLevel: 9})
		]))
		.pipe(gulp.dest(dest))
	;
});

gulp.task('css', function() {
	var src  = ['public-dev/css/**/*.css'];
	var dest = 'public/css';
	return gulp.src(src)
		.pipe(prune({
			dest: dest,
			ext: ['.css.map', '.css']
		}))
		.pipe(newer({
			dest: dest,
			ext: '.css'
		}))
		.pipe(sourcemaps.init())
		.pipe(
			postcss([
				require('autoprefixer')({browsers: '> 5%'}),
				require('cssnano')()
			])
		)
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(dest))
	;
});

gulp.task('js', function() {
	var src  = ['./public-dev/js/*.js'];
	var dest = 'public/js';
	return gulp.src(src)
		.pipe(prune({
			dest: dest,
			ext: ['.js.map', '.js']
		}))
		.pipe(newer({ dest: dest, ext: '.js' }))
		.pipe(sourcemaps.init())
		.pipe(uglify())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('public/js'))
	;
});

gulp.task('copy', function(done) {
	gulp.src([
		'./public-dev/*',
		'!./public-dev/css',
		'!./public-dev/img',
		'!./public-dev/js'
	])
		.pipe(exec('ln -rfs "<%= file.path %>" ../public/', { cwd: 'public-dev' }))
	;

	done();
});

gulp.task('deploy', function(done) {
	rsync({
		exclude: ['/.internal/', '/.settings/', '/.project', '/.buildpath', '/.git/', '/node_modules/'],
		args: ['-avHz', '--password-file=.internal/password'],
		src: '.',
		dest: config.deploy_target,
		dryRun: false,
		delete: true
	}, function (error, stdout, stderr, cmd) {
//		console.log(cmd);
		console.log(stdout);
		console.log(stderr);
		done();
	});
});

gulp.task('download', function(done) {
	rsync({
		exclude: ['/.internal/', '/.settings/', '/.project', '/.buildpath', '/.git/', '/node_modules/', 'gulpfile.js'],
		args: ['-avHz', '--password-file=.internal/password'],
		dest: './',
		src: config.deploy_target,
		dryRun: false,
		delete: true
	}, function (error, stdout, stderr, cmd) {
		//console.log(cmd);
		console.log(stdout);
		console.log(stderr);
		done();
	});
});

gulp.task('default', gulp.parallel('images', 'css', 'js', 'copy'));
