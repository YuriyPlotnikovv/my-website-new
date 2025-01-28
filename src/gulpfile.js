const gulp = require("gulp");
const plumber = require("gulp-plumber");
const sourcemaps = require("gulp-sourcemaps");
const sass = require("gulp-sass")(require("node-sass"));
const postcss = require("gulp-postcss");
const autoprefixer = require("autoprefixer");
const csso = require("postcss-csso");
const rename = require("gulp-rename");
const uglify = require("gulp-uglify-es").default;
const imagemin = require("gulp-imagemin");
const webp = require("gulp-webp");
const svgstore = require("gulp-svgstore");
const del = require("del");
const browserSync = require("browser-sync").create();
const concat = require("gulp-concat");
const bulk = require("gulp-sass-bulk-importer");
const cleanCSS = require("gulp-clean-css");
const babel = require("gulp-babel");
const cached = require("gulp-cached");
const remember = require("gulp-remember");
const notify = require("gulp-notify");

const paths = {
  styles: {
    src: "css/style.scss",
    dest: "../public/css",
    watch: "css/**/*.scss"
  },
  scripts: {
    src: "js/modules/*.js",
    dest: "../public/js",
    watch: "js/modules/**/*.js"
  },
  images: {
    src: ['img/**/*.{jpg,jpeg,png}', '!img/svg/**/*'],
    dest: '../public/img',
    watch: 'img/**/*.{jpg,jpeg,png,svg}'
  },
  sprite: {
    src: "img/svg/*.svg",
    dest: "../public/img"
  },
  copy: {
    src: [
      "js/vendor/*.js",
      "css/vendor/*.css",
      "fonts/*.{woff,woff2}",
      "img/*.gif"
    ],
    dest: "../public/"
  },
  clean: "../public"
};

// Styles
const styles = () => {
  return gulp
    .src(paths.styles.src)
    .pipe(plumber({ errorHandler: notify.onError("Error: <%= error.message %>") }))
    .pipe(sourcemaps.init())
    .pipe(bulk())
    .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
    .pipe(postcss([autoprefixer(), csso()]))
    .pipe(cleanCSS({ level: 2 }))
    .pipe(concat("style.min.css"))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browserSync.stream());
};
exports.styles = styles;

// Scripts
const scripts = () => {
  return gulp
    .src(paths.scripts.src)
    .pipe(cached('scripts'))
    .pipe(sourcemaps.init())
    .pipe(babel({ presets: ["@babel/env"] }))
    .pipe(uglify())
    .pipe(remember('scripts'))
    .pipe(concat("script.min.js"))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(browserSync.stream());
};
exports.scripts = scripts;

// Images
const images = () => {
  return gulp.src(paths.images.src)
    .pipe(imagemin())
    .pipe(webp({ quality: 80 }))
    .pipe(gulp.dest(paths.images.dest))
    .on('end', () => {
      gulp.src('img/**/*.webp')
        .pipe(gulp.dest(paths.images.dest));
    });
};
exports.images = images;

// Sprite
const sprite = () => {
  return gulp
    .src(paths.sprite.src)
    .pipe(svgstore({ inlineSvg: true }))
    .pipe(rename("sprite.svg"))
    .pipe(gulp.dest(paths.sprite.dest));
};
exports.sprite = sprite;

// Copy
const copy = (done) => {
  gulp
    .src(paths.copy.src, { base: '.' })
    .pipe(gulp.dest(paths.copy.dest));
  done();
};
exports.copy = copy;

// Clean
const clean = () => {
  return del([paths.clean], { force: true });
};
exports.clean = clean;

// Build
const build = gulp.series(
  clean,
  copy,
  images,
  gulp.parallel(styles, scripts, sprite)
);
exports.build = build;

// Watch
const watchFiles = () => {
  gulp.watch(paths.styles.watch, styles);
  gulp.watch(paths.scripts.watch, scripts);
  gulp.watch(paths.images.watch, images);
};

// Default
exports.default = gulp.series(
  build,
  gulp.parallel(watchFiles, () => {
    browserSync.init({
      server: {
        baseDir: "../public"
      }
    });
  })
);
