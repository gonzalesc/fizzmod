var gulp = require('gulp');
var minify = require('gulp-minify');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');


gulp.task('compress', function() {
  gulp.src('assets/js/interaction.js')
    .pipe(minify({
        ext:{
            //src:'.js',
            min:'.min.js'
        },
        exclude: ['tasks'],
        noSource: 'yes',
        ignoreFiles: ['.min.js']
    }))
    .pipe(gulp.dest('assets/js/'));

    gulp.src('assets/css/style.css')
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('assets/css/'));
});

//Tarea por defecto
gulp.task('default',['compress']);