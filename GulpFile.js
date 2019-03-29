const gulp = require('gulp');
const watch = require('gulp-watch');
const gulpZip = require('gulp-zip');

const sourceFiles = 'app/**/*.*';
const distFiles = 'wp/wp-content/plugins/nhl-stats';
 
gulp.task('copy', function() {
  gulp.src(sourceFiles)
      .pipe(gulp.dest(distFiles));
});

gulp.task('watch', function(){
	gulp.watch('app/**/*.*', ['copy']) ;
});

gulp.task('build', function(){
  gulp.src(sourceFiles)
      .pipe(gulpZip(`nhl-stats.zip`))
      .pipe(gulp.dest('dist'))
});