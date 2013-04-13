var
    gulp         = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    cache        = require('gulp-cache'),
    concat       = require('gulp-concat'),
    copy         = require('gulp-copy'),
    less         = require('gulp-less'),
    livereload   = require('gulp-livereload'),
    minifycss    = require('gulp-minify-css'),
    size         = require('gulp-size'),
    uglify       = require('gulp-uglify'),
    watch        = require('gulp-watch')
;

var config = {
    srcCss: [
        'assets/less/*.less',
        'assets/less/*/*.less'
    ],
    srcJs: [
        'node_modules/jquery/dist/jquery.js',
        'assets/js/*/*.js'
    ],
    destJs:    'web/assets/js',
    destCss:   'web/assets/css'
}

gulp.task('js', function () {

    return gulp
        .src(config.srcJs)
        .pipe(concat('all.js'))
        .pipe(gulp.dest(config.destJs))
        .pipe(size())
        .pipe(livereload())
    ;
});

gulp.task('jsMin', function () {
    return gulp
        .src(config.srcJs)
        .pipe(concat('all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(config.destJs))
        .pipe(size())
        .pipe(livereload())
    ;
});

gulp.task('css', function () {
    return gulp
        .src(config.srcCss)
        .pipe(concat('all.css'))
        .pipe(less())
        .pipe(gulp.dest(config.destCss))
        .pipe(size())
        .pipe(livereload())
    ;
});

gulp.task('cssMin', function () {
    return gulp
        .src(config.srcCss)
        .pipe(concat('all.min.css'))
        .pipe(less())
        .pipe(minifycss())
        .pipe(gulp.dest(config.destCss))
        .pipe(size())
        .pipe(livereload())
    ;
});

gulp.task('watch', function () {
    livereload.listen(
        35729,
        function (err) {
            gulp.watch(config.srcCss, ['css']);
            gulp.watch(config.srcJs, ['js']);
        }
    );
});

gulp.task('default', ['js', 'css']);
gulp.task('min', ['jsMin', 'cssMin']);
