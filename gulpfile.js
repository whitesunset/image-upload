
var gulp = require('gulp');
var gulpif = require('gulp-if');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var env = 'prod';;

gulp.task('js', function () {
	return gulp.src([
		'bower_components/js-md5/build/md5.min.js',
		'bower_components/clipboard/dist/clipboard.min.js',
		'bower_components/jquery/dist/jquery.min.js',
		'bower_components/underscore/underscore-min.js',
		'bower_components/backbone/backbone-min.js',
		'bower_components/backbone-deep-model/distribution/deep-model.js',
		'bower_components/backbone.stickit/backbone.stickit.js',
		'bower_components/backbone.paginator/lib/backbone.paginator.min.js',
		'bower_components/backbone.collectionView/dist/backbone.collectionView.js',
		'bower_components/bootstrap/dist/js/bootstrap.min.js',
		'bower_components/bootstrap-material-design/dist/js/material.min.js',
		'bower_components/bootstrap-material-design/dist/js/ripples.min.js',
		'src/public/js/**/*.js'
		])
		.pipe(concat('script.min.js'))
		.pipe(gulpif(env === 'prod', uglify()))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('public'));
});

gulp.task('css', function () {
	return gulp.src([
		'bower_components/bootstrap/dist/css/bootstrap.css',
		'bower_components/bootstrap-material-design/dist/css/bootstrap-material-design.min.css',
		'bower_components/bootstrap-material-design/dist/css/ripples.min.css',
		'src/public/scss/main.scss'])
		.pipe(gulpif(/[.]scss/, sass()))
		.pipe(concat('style.min.css'))
		.pipe(gulpif(env === 'prod', uglifycss()))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('public'));
});

gulp.task('build', ['css', 'js']);

gulp.task('watch', function () {
	env = 'dev';
	gulp.start('css', 'js')
	var css = gulp.watch('src/public/scss/**/*.scss', ['css']);
	var js = gulp.watch('src/public//js/**/*.js', ['js']);
});

gulp.task('default', ['build']);