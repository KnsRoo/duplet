const gulp = require('gulp');
const babel = require('gulp-babel');
const jsmin = require('gulp-jsmin');
const rename = require('gulp-rename');

gulp.task('default', ['build-dev', 'build-prod']);

gulp.task('build-prod', () => {

	gulp.src('./src/clone-child.js')
		.pipe(babel({ presets: ['latest'] }))
		.pipe(jsmin())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest('./'));

});

gulp.task('build-dev', () => {

	gulp.src('./src/clone-child.js')
		.pipe(babel({ presets: ['latest'] }))
		.pipe(gulp.dest('./'));

});

gulp.task('watch', () => {

	gulp.watch('./src/*.js', ['default']);

});
