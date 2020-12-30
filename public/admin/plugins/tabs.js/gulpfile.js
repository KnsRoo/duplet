const gulp = require('gulp');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const jsmin = require('gulp-jsmin');
const cssmin = require('gulp-cssmin');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const sass = require('gulp-sass');

gulp.task('default', ['build-js', 'build-styles']);

gulp.task('build-js', () => {

	gulp.src('./src/*.js')
		.pipe(sourcemaps.init())
		.pipe(babel())
		.pipe(jsmin())
		.pipe(concat('tabs.min.js'))
		.pipe(sourcemaps.write('/maps'))
		.pipe(gulp.dest('./public/'));

});

gulp.task('build-styles', () => {

	gulp.src('./src/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer())
		.pipe(cssmin())
		.pipe(rename({suffix: '.min'}))
		.pipe(sourcemaps.write('/maps'))
		.pipe(gulp.dest('./public'));

});
