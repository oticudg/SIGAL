var gulp = require('gulp')
  concat = require('gulp-concat')
  uglify = require('gulp-uglify')
  uglifycss = require('gulp-uglifycss');
  jsValidate = require('gulp-jsvalidate');

gulp.task('js', function(){
	gulp.src('resources/assets/js/*.js')
	.pipe(concat('deposito.js'))
	.pipe(uglify({mangle: false}))
	.pipe(gulp.dest('public/js'));
})

gulp.task('validate-js', function(){
  return gulp.src('resources/assets/js/*.js')
		.pipe(jsValidate());
});

gulp.task('css', function(){
	gulp.src('resources/assets/css/global.css')
	.pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('public/css/'));
})

gulp.task('default', function(){
  gulp.watch('resources/assets/js/*.js', ['validate-js']);
  gulp.watch('resources/assets/js/*.js', ['js']);
  gulp.watch('resources/assets/css/*.css', ['css']);
});
