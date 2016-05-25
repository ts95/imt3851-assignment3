var gulp = require('gulp');
var sass = require('gulp-sass');
var webpack = require('gulp-webpack');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var babel = require('gulp-babel');
var browserSync = require('browser-sync');

var pkg = require('./package.json');

gulp.task('browser', function() {
    browserSync({
        proxy: 'http://localhost:' + pkg.port,
    });
});

gulp.task('reload', function() {
    browserSync.reload();
});

gulp.task('webpack', function() {
    return gulp.src('./assets/js/index.js')
        .pipe(webpack(require('./webpack.config.js')))
        .pipe(gulp.dest('./public/js'))
        .pipe(browserSync.stream());
});

gulp.task('sass', function() {
    return gulp.src('./assets/sass/main.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false,
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./public/css'))
        .pipe(browserSync.stream());
});

gulp.task('watch', function() {
    gulp.watch('./*.php', ['reload']);
    gulp.watch('./assets/js/**/*.js', ['webpack']);
    gulp.watch('./assets/vue/**/*.vue', ['webpack']);
    gulp.watch('./assets/sass/**/*.scss', ['sass']);
});

gulp.task('build', ['webpack', 'sass']);

gulp.task('default', ['build', 'watch', 'browser']);
