var gulp = require('gulp'),
    notify = require('gulp-notify'),
    phpunit = require('gulp-phpunit'),
    phpspec = require('gulp-phpspec');

// Run all PHPUnit tests
gulp.task('phpunit', function () {
    gulp.src('phpunit.xml.dist')
        .pipe(phpunit('./bin/phpunit', {
            notify: true,
            clear: true
        }))
        .on('error', notify.onError({
            title: 'Awww shit!',
            message: 'Your tests failed!',
            icon: __dirname + '/node_modules/gulp-phpunit/assets/test-fail.png'
        }))
        .pipe(notify({
            title: 'Awww yeah!',
            message: 'All green!',
            icon: __dirname + '/node_modules/gulp-phpunit/assets/test-pass.png'
        }));
});

// Run all specs
gulp.task('phpspec', function () {
    gulp.src('spec/**/*.php')
        .pipe(phpspec('./bin/phpspec run', {
            notify: true,
            clear: true
        }))
        .on('error', notify.onError({
            title: 'Awww shit!',
            message: 'Your tests failed!',
            icon: __dirname + '/node_modules/gulp-phpspec/assets/test-fail.png'
        }))
        .pipe(notify({
            title: 'Awww yeah!',
            message: 'All green!',
            icon: __dirname + '/node_modules/gulp-phpspec/assets/test-pass.png'
        }));
});

// Aggregate task to run all tests
gulp.task('test', ['phpunit', 'phpspec']);

// Keep an eye on PHP files for changes...
gulp.task('watch', function () {
    //gulp.watch(['tests/**/*.php', 'src/**/*.php'], ['phpunit']);
    gulp.watch(['spec/**/*.php', 'src/**/*.php'], ['phpspec']);
});

// What tasks does running gulp trigger?
gulp.task('default', ['phpspec', 'watch']);
