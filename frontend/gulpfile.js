'use strict';
const gulp = require('gulp'),
	$ = require('gulp-load-plugins')({
		lazy: true
	});

const path = {
	dist: '../www/dist/',
	src: './src/',
	distHTML: '../www/'
}

gulp.task('clean', () => {
	return gulp.src(path.dist, {
			read: false
		})
		.pipe($.clean({
			force: true
		}));
});

gulp.task('connect', () => {
	$.connect.server({
		port: 5000,
		root: path.distHTML,
		livereload: true
	});
});

gulp.task('html', () => {
	return gulp.src(path.src + 'index.html')
		.pipe($.w3cjs())
		.pipe($.htmlmin({
			collapseWhitespace: true
		}))
		.pipe(gulp.dest(path.distHTML))
		.pipe($.connect.reload());
});

gulp.task('imgs:dist', () => {
	return gulp.src(path.src + 'img/**/*.*')
		.pipe(gulp.dest(path.dist + 'img'))
		.pipe($.connect.reload());
});

gulp.task('favicons:dist', () => {
	return gulp.src(path.src + 'favicons/**/*.*')
		.pipe(gulp.dest(path.dist + 'favicons'))
		.pipe($.connect.reload());
});

gulp.task('imgs', ['imgs:dist', 'favicons:dist']);

gulp.task('sass', () => {
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

gulp.task('js', () => {
	return gulp.src(path.src + 'js/**/*.js')
		.pipe($.babel({
			presets: ['env']
		}))
		.pipe($.uglify().on("error", $.notify.onError({
			message: "<%= error.message %>",
			title: "JS Error: "
		})))
		.pipe(gulp.dest(path.dist + 'js'))
		.pipe($.connect.reload());
});

gulp.task('watch', () => {
	gulp.watch(path.src + 'scss/**/*.scss', ['sass']);
	gulp.watch(path.src + 'js/**/*.js', ['js']);
	gulp.watch(path.src + 'index.html', ['html']);
	gulp.watch([path.src + 'img/**/*.*', path.src + 'favicons/**/*.*'], ['imgs']);
});

gulp.task('default', ['clean', 'connect', 'watch'], () => {
	gulp.start('html');
	gulp.start('sass');
	gulp.start('imgs');
	gulp.start('js');
});