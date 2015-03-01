var gulp = require('gulp'),
	less = require('gulp-less'),
	watch = require('gulp-watch')
	rename = require('gulp-rename');
 
gulp.task('less', function () {	
  gulp.src('less/*.less')
    .pipe(less())
    .pipe(gulp.dest('./css'));
});

gulp.task('bootstrap', function() {
	gulp.src('less/custom-bootstrap/custom.less')
		.pipe(less())
		.pipe(rename('bootstrap.css'))
		.pipe(gulp.dest('css'));
});

gulp.task('default', ['less', 'bootstrap']);


var watcher = gulp.task('watch', function() {
	gulp.watch('less/*.less', ['less']);
	gulp.watch('less/custom-bootstrap/*.less', ['bootstrap']);
})

watcher.on('change', function (event) {
   console.log('Event type: ' + event.type); // added, changed, or deleted
   console.log('Event path: ' + event.path); // The path of the modified file
});

