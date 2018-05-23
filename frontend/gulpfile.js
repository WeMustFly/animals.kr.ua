'use strict';
var gulp = require('gulp'),
	$ = require('gulp-load-plugins')({lazy: true});

var path = {
	dist: './dist/',
	src: './src/',
}

gulp.task('clean', function () {
	return gulp.src(path.dist, {
			read: false
		})
		.pipe($.clean());
});

gulp.task('connect', function () {
	$.connect.server({
		port: 5000,
		root: 'dist',
		livereload: true
	});
});


gulp.task('htmlmin', function () {
	return gulp.src(path.src + 'index.html')
		.pipe($.htmlmin({
			collapseWhitespace: true
		}))
		.pipe(gulp.dest(path.dist))
		.pipe($.connect.reload());
});

gulp.task('imgs', function () {
	return gulp.src(path.src + 'img/**/*.*')
		.pipe(gulp.dest(path.dist))
		.pipe($.connect.reload());
});

gulp.task('sass', function () {
	return gulp.src(path.src + 'scss/**/*.scss')
		.pipe($.sass({
			outputStyle: 'compressed'
		}).on("error", $.notify.onError({
			message: "<%= error.message %>",
			title: "SASS Error: "
		})))
		.pipe($.autoprefixer())
		.pipe(gulp.dest(path.dist + 'css'))
		.pipe($.connect.reload());
})

gulp.task('js', function () {
	return gulp.src(path.src + 'js/**/*.js')
		.pipe($.uglify().on("error", $.notify.onError({
			message: "<%= error.message %>",
			title: "JS Error: "
		})))
		.pipe(gulp.dest(path.dist + 'js'))
		.pipe($.connect.reload());
});

gulp.task('valid', function () {
	gulp.src(path.dist + 'index.html')
		.pipe($.w3cjs())
		.pipe($.w3cjs.reporter());
});

gulp.task('watch', function () {
	gulp.watch(path.src + 'scss/**/*.scss', ['sass']);
	gulp.watch(path.src + 'js/**/*.js', ['js']);
	gulp.watch(path.src + 'index.html', ['htmlmin']);
	gulp.watch(path.src + 'img/**/*.*', ['imgs']);
});

gulp.task('default', ['clean', 'connect', 'watch'], function () {
	gulp.start('htmlmin');
	gulp.start('sass');
	gulp.start('imgs');
	gulp.start('js');
});