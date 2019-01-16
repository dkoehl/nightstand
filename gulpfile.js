'use strict';

var autoprefixer = require('gulp-autoprefixer');
var del = require('del');
var gulp = require('gulp');
var runSequence = require('run-sequence');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify-css');
var concat = require('gulp-concat');
var browserSync = require('browser-sync').create();


// Set the browser that you want to support
const AUTOPREFIXER_BROWSERS = [
    'ie >= 10',
    'ie_mob >= 10',
    'ff >= 30',
    'chrome >= 34',
    'safari >= 7',
    'opera >= 23',
    'ios >= 7',
    'android >= 4.4',
    'bb >= 10'
];

// minifies CSS
gulp.task('styles', function () {
    return gulp.src(['node_modules/bootstrap/dist/css/bootstrap.min.css', 'node_modules/@fortawesome/fontawesome-free/css/all.css', 'Build/Css/style.css'])
        .pipe(autoprefixer({browsers: AUTOPREFIXER_BROWSERS}))
        .pipe(minify())
        .pipe(concat('style.min.css'))
        .pipe(gulp.dest('./assets/css'))
        .pipe(browserSync.reload({
            stream: true
        }))
});

// minifies JS
gulp.task('scripts', function () {
    return gulp.src(['node_modules/jquery/dist/jquery.min.js', 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'node_modules/@fortawesome/fontawesome-free/js/all.js', 'Build/Javascript/Miscs.js', 'Build/Javascript/Datetime.js', 'Build/Javascript/Background.js', 'Build/Javascript/Sbahnticker.js', 'Build/Javascript/Ubahnticker.js', 'Build/Javascript/Weatherticker.js', 'Build/Javascript/Realtimetraindata.js', 'Build/Javascript/Webcamimages.js', 'Build/Javascript/Salutation.js', 'Build/Javascript/Loading.js'])
        .pipe(concat('bundle.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js'))
        .pipe(browserSync.reload({
            stream: true
        }))
});

// copies fonts
gulp.task('fonts', function() {
    return gulp.src(['./node_modules/@fortawesome/fontawesome-free/webfonts/fa-regular-400.woff2', 'node_modules/@fortawesome/fontawesome-free/webfonts/fa-solid-900.woff2'])
        .pipe(gulp.dest('./assets/webfonts'))
});
//
// gulp.task('icons', function () {
//     return gulp.src(['Build/Icons/*'])
//         .pipe(gulp.dest('./assets/Icons'))
// });
// gulp.task('images', function () {
//     return gulp.src(['Build/images/*'])
//         .pipe(gulp.dest('./assets/images'))
// });
// gulp.task('videos', function () {
//     return gulp.src(['Build/videos/**/*'])
//         .pipe(gulp.dest('./assets/videos'))
// });

// cleans dist directory
// gulp.task('clean', ()=> del(['dist']));

// runner
gulp.task('default', function () {
    runSequence(
        // 'icons',
        'styles',
        'fonts',
        'scripts'
        // 'images',
        // 'videos'
    );
});
/**
 * watches for css and js changes
 */
gulp.task('watch', function () {
    gulp.watch('Build/Css/**/*.css', ['styles']);
    gulp.watch('Build/Javascript/**/*.js', ['scripts']);
});

/**
 * Live browser changes
 */
// gulp.task('browserSync', function () {
//     browserSync.init({
//         server : {
//             baseDir : 'dist'
//         }
//     })
// });