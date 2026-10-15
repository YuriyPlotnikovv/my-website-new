import gulp from 'gulp';
import fs from 'fs';
import path from 'path';
import plumber from 'gulp-plumber';
import sourcemaps from 'gulp-sourcemaps';
import sass from 'gulp-dart-sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import csso from 'postcss-csso';
import rename from 'gulp-rename';
import terser from 'gulp-terser';
import imagemin from 'gulp-imagemin';
import imageminMozjpeg from 'imagemin-mozjpeg';
import imageminOptipng from 'imagemin-optipng';
import imageminSvgo from 'imagemin-svgo';
import webp from 'gulp-webp';
import svgstore from 'gulp-svgstore';
import cheerio from 'gulp-cheerio';
import {deleteAsync as del} from 'del';
import concat from 'gulp-concat';
import cleanCSS from 'gulp-clean-css';
import babel from 'gulp-babel';
import cached from 'gulp-cached';
import remember from 'gulp-remember';
import notify from 'gulp-notify';

const paths = {
  styles: {
    global: [
      './global/fonts',
      './global/variables',
      './global/settings',
      './global/animations'
    ],
    blocks: 'src/css/blocks',
    pages: 'src/css/pages',
    src: 'src/css/style.scss',
    dest: 'public/css',
    watch: 'src/css/**/*.scss'
  },
  scripts: {
    src: 'src/js/modules/*.js',
    dest: 'public/js',
    watch: 'src/js/modules/**/*.js'
  },
  images: {
    src: ['src/img/**/*.{jpg,jpeg,png,svg}', '!src/img/og-image.png', '!src/img/svg/**/*'],
    dest: 'public/img',
    watch: 'src/img/**/*.{jpg,jpeg,png,svg}'
  },
  sprite: {
    src: 'src/img/svg/**/*.svg',
    dest: 'public/img'
  },
  copy: {
    src: [
      'src/js/vendor/*.js',
      'src/css/vendor/*.css',
      'src/fonts/*.{woff,woff2}',
      'src/img/*.gif',
      'src/img/og-image.png',
    ],
    dest: 'public/'
  },
  clean: 'public'
};

// Styles
const generateStyleFile = (done) => {
  const styleFilePath = 'src/css/style.scss';

  const readDir = (dir) => new Promise((resolve, reject) => {
    fs.readdir(dir, (err, files) => {
      if (err) {
        reject(err);
        return;
      }
      resolve(files.filter(file => path.extname(file) === '.scss'));
    });
  });

  Promise.all([readDir(paths.styles.blocks), readDir(paths.styles.pages)])
    .then(([blockFiles, pageFiles]) => {
      let content = paths.styles.global.map(filePath => `@use "${filePath}";`).join('\n') + '\n\n';
      blockFiles.forEach(file => {
        content += `@use "./blocks/${path.basename(file, '.scss')}";\n`;
      });
      pageFiles.forEach(file => {
        content += `@use "./pages/${path.basename(file, '.scss')}";\n`;
      });
      fs.writeFile(styleFilePath, content, done);
    })
    .catch(done);
};

const styles = () => {
  return gulp.src(paths.styles.src, {encoding: false})
    .pipe(plumber({errorHandler: notify.onError('Error: <%= error.message %>')}))
    .pipe(sourcemaps.init())
    .pipe(sass.sync({
      outputStyle: 'compressed',
      charset: false
    }).on('error', sass.logError))
    .pipe(postcss([autoprefixer(), csso()]))
    .pipe(cleanCSS({level: 2}))
    .pipe(concat('style.min.css'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.styles.dest));
};

// Scripts
const scripts = () => {
  return gulp.src(paths.scripts.src, {encoding: false})
    .pipe(cached('scripts'))
    .pipe(sourcemaps.init())
    .pipe(babel({
      'presets': [
        [
          '@babel/preset-env'
        ]
      ]
    }))
    .pipe(terser())
    .pipe(remember('scripts'))
    .pipe(concat('script.min.js'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.scripts.dest));
};

// Images
const imagesRaster = () => {
  return gulp.src(['src/img/**/*.{jpg,jpeg,png}', '!src/img/og-image.png'], {encoding: false})
    .pipe(imagemin([
      imageminMozjpeg({quality: 75, progressive: true}),
      imageminOptipng({optimizationLevel: 5})
    ]))
    .pipe(webp({quality: 80}))
    .pipe(gulp.dest(paths.images.dest))
    .on('end', () => {
      gulp.src('src/img/**/*.webp', {encoding: false})
        .pipe(gulp.dest(paths.images.dest));
    });
};

const imagesVector = () => {
  return gulp.src(['src/img/**/*.svg', '!src/img/svg/**/*'], {encoding: false})
    .pipe(imagemin([
      imageminSvgo({
        plugins: [
          {name: 'removeViewBox'},
          {name: 'cleanupIds', active: false}
        ]
      })
    ]))
    .pipe(gulp.dest(paths.images.dest));
};

const images = gulp.parallel(imagesRaster, imagesVector);

// Sprite
const sprite = () => {
  return gulp.src(paths.sprite.src, {encoding: false})
    .pipe(cheerio({
      run: function ($, file) {
        const fileName = path.basename(file.relative, path.extname(file.relative));
        $('*').each(function () {
          const id = $(this).attr('id');
          if (id) {
            const newId = `${fileName}-${id}`;
            $(this).attr('id', newId);

            $(`[xlink\\:href="#${id}"]`).attr('xlink:href', `#${newId}`);
            $(`[href="#${id}"]`).attr('href', `#${newId}`);

            $(`[fill="url(#${id})"]`).attr('fill', `url(#${newId})`);
            $(`[stroke="url(#${id})"]`).attr('stroke', `url(#${newId})`);

            $('[style]').each(function () {
              const styleVal = $(this).attr('style');
              if (styleVal && styleVal.includes(`url(#${id})`)) {
                const newStyleVal = styleVal.replace(new RegExp(`url\\(#${id}\\)`, 'g'), `url(#${newId})`);
                $(this).attr('style', newStyleVal);
              }
            });

            $('style').each(function () {
              const styleContent = $(this).html();
              if (styleContent && styleContent.includes(`url(#${id})`)) {
                const updatedStyleContent = styleContent.replace(new RegExp(`url\\(#${id}\\)`, 'g'), `url(#${newId})`);
                $(this).html(updatedStyleContent);
              }
            });
          }

          const className = $(this).attr('class');
          if (className) {
            const newClassName = className.split(' ').map(cls => `${fileName}-${cls}`).join(' ');
            $(this).attr('class', newClassName);

            $('style').each(function () {
              const styleContent = $(this).html();
              const updatedStyleContent = className.split(' ').reduce((content, cls) => {
                const regex = new RegExp(`\\.${cls}`, 'g');
                return content.replace(regex, `.${fileName}-${cls}`);
              }, styleContent);
              $(this).html(updatedStyleContent);
            });
          }
        });
      },
      parserOptions: { xmlMode: true }
    }))
    .pipe(svgstore({inlineSvg: true}))
    .pipe(rename('sprite.svg'))
    .pipe(gulp.dest(paths.sprite.dest));
};

// Copy
const copy = (done) => {
  gulp.src(paths.copy.src, {base: 'src', encoding: false})
    .pipe(gulp.dest(paths.copy.dest));
  done();
};

// Clean
const clean = () => {
  return del([paths.clean], {force: true});
};

// Build
const build = gulp.series(
  clean,
  copy,
  images,
  generateStyleFile,
  gulp.parallel(styles, scripts, sprite)
);

// Watch
const watchFiles = () => {
  gulp.watch(paths.styles.watch, styles);
  gulp.watch(paths.scripts.watch, scripts);
  gulp.watch(paths.images.watch, images);
};

export {
  generateStyleFile,
  styles,
  scripts,
  imagesRaster,
  imagesVector,
  images,
  sprite,
  copy,
  clean,
  build,
  watchFiles
};

export default build;
