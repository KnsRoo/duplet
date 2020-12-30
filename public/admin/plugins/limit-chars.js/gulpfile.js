const gulp = require('gulp');
const babel = require('gulp-babel');
const rename = require('gulp-rename');
const jsmin = require('gulp-jsmin');

gulp.task('default', ['build-dev', 'build-prod']);

gulp.task('build-dev', () => {
	return gulp.src('./src/limit-chars.js')
		.pipe(babel({
			presets: ['latest']
		}))
		.pipe(gulp.dest('./'));
});

gulp.task('build-prod', () => {
	return gulp.src('./src/limit-chars.js')
		.pipe(babel({
			presets: ['latest']
		}))
		.pipe(jsmin())
		.pipe(rename('limit-chars.min.js'))
		.pipe(gulp.dest('./'));
});

gulp.task('watch', () => {

	var watcher = gulp.watch('./src/*.js', ['dafault']);

});
