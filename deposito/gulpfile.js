var gulp = require('gulp')
  concat = require('gulp-concat')
  uglify = require('gulp-uglify')
  uglifycss = require('gulp-uglifycss');

gulp.task('js', function(){
	gulp.src('resources/assets/js/*.js')
	.pipe(concat('deposito.js'))
	.pipe(uglify({mangle: false}))
	.pipe(gulp.dest('public/js'));
})

gulp.task('css', function(){
	gulp.src('resources/assets/css/global.css')
	.pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('public/css/'));
})

gulp.task('default', function(){
  gulp.watch('resources/assets/js/*.js', ['js']);
});
