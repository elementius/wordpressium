'use strict';

// Core Node
import * as fs from 'fs';

// Core Gulp
import gulp from 'gulp';

// Config Values
import paths from './gulpfile.json';

// Other Tools
import notifier from 'node-notifier';
import newer from 'gulp-newer';

// Style Processing
import sass from 'gulp-sass';
import sassGlob from 'gulp-sass-bulk-import';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import normalize from 'postcss-normalize';

const neat = require('node-neat').includePaths;

// Javascript Processing
import browserify from 'browserify';
import babelify from 'babelify';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';
import es from 'event-stream';
import rename from 'gulp-rename';
import uglify from 'gulp-uglify';

// Image Processing
import imagemin from 'gulp-imagemin';
import imageminJpegoptim from 'imagemin-jpegoptim';

const packageJson = JSON.parse(fs.readFileSync('./package.json'));
const targets = packageJson.browserslist;

const env = process.env.NODE_ENV; // eslint-disable-line

function emitLog(stage, err) {
    if (err) {
        notifier.notify({
            'title': `ERROR: ${stage}`,
            'message': `There was an error within ${stage}`
        });
        console.log(err); // eslint-disable-line
    }
}

// Styles Task
gulp.task('styles', () => {
    let processors = [
        autoprefixer(),
        normalize()
    ];
    if (env !== 'development') {
        processors.push(cssnano({discardUnused: {fontFace: false}}));
    }

    let tasks = paths.themeName.map((theme) => {
        return gulp.src(`resources/${theme}/${paths.src.styles}/**/*.scss`)
            .pipe(sassGlob())
            .pipe(
                sass({includePaths: ['styles'].concat(neat)})
                    .on('error', (err) => {
                        emitLog('styles', err);
                        this.emit('end');
                    })
            )
            .pipe(postcss(processors))
            .pipe(gulp.dest(`${paths.dest.theme}/${theme}/styles/`));
    })
    return es.merge(tasks);
});

// Scripts Task
gulp.task('scripts', () => {
    let tasks = paths.themeName.map((theme) => {
        return gulp.src(`resources/${theme}/${paths.src.scripts}/**/*.js`, (err, files) => {
        let tasks = files.map((entry) => {
            return browserify({entries: [entry]})
                .transform(babelify, {
                    presets: [[
                        'env', {
                            'targets': targets[env]
                        }]]
                })
                .bundle()
                .pipe(source(entry))
                .pipe(buffer())
                .pipe(env !== 'development' ? uglify() : buffer())
                .pipe(rename((fileObj) => {
                    fileObj.dirname = '';
                }))
                .pipe(gulp.dest(`${paths.dest.theme}/${theme}/scripts/`));
        });
        es.merge(tasks);
    })
        .on('end', (err) => {
            emitLog('scripts', err);
        });
    })
    return es.merge(tasks);
});

// Images Task
gulp.task('images', () => {
    let tasks = paths.themeName.map((theme) => {
        if (env === 'development') {
            return gulp.src(`resources/${theme}/${paths.src.images}/**/*.+(jpg|jpeg|gif|png|svg)`)
                .pipe(newer(`${paths.dest.theme}/${theme}/images/`))
                .pipe(gulp.dest(`${paths.dest.theme}/${theme}/images/`))
                .on('end', (err) => {
                    emitLog('images', err);
                });
        }
        return gulp.src(`resources/${theme}/${paths.src.images}/**/*.+(jpg|jpeg|gif|png|svg)`)
            .pipe(imagemin([
                imagemin.gifsicle({interlaced: true}),
                imagemin.optipng({optimzationLevel: 5}),
                imagemin.svgo({
                    plugins: [
                        {
                            cleanupIDs: false,
                            removeEmptyAttrs: false,
                            removeViewBox: false
                        }
                    ]
                }),
                imageminJpegoptim({
                    max: 85,
                    progressive: true
                })
            ]))
            .pipe(gulp.dest(`${paths.dest.theme}/${theme}/images`))
            .on('end', (err) => {
                emitLog('images', err);
            });
    })
    return es.merge(tasks);
});

// Watch Task
gulp.task('watch', ['default'], () => {
    let tasks = paths.themeName.map((theme) => {
        gulp.watch(`resources/${theme}/${paths.src.styles}/**/*.scss`, ['styles']);
        gulp.watch(`resources/${theme}/${paths.src.scripts}/**/*.js`, ['scripts']);
        gulp.watch(`resources/${theme}/${paths.src.images}/**/*.+(jpg|jpeg|gif|png|svg)`, ['images']);
    })
});

// Compilation Task
gulp.task('default', ['styles', 'scripts', 'images']);
