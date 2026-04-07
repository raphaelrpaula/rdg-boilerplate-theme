import gulp from "gulp";
import * as dartSass from "sass";
import gulpSass from "gulp-sass";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import rename from "gulp-rename";
import browser_sync from "browser-sync";
import babel from "gulp-babel";
import uglify from "gulp-uglify";
import concat from "gulp-concat";
import webp from "gulp-webp";

const sass = gulpSass(dartSass);
browser_sync.create();

const dev = "./assets/_dev";

var paths = {
  styles: {
    src: `${dev}/css/scss/**/*.scss`,
    dest: `${dev}/css`,
    dest_min: "./assets/css",
  },
  scripts: {
    src: `${dev}/scripts/**/*.js`,
    dest: "./assets/js",
  },
  img: {
    src: `${dev}/img/**/*.{jpg,png,jpeg,tif,tiff}`,
    dest: "./assets/img",
  },
};

export function styles() {
  return gulp
    .src(paths.styles.src)
    .pipe(sass().on("error", sass.logError))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(
      sass({
        style: "compressed",
      }).on("error", sass.logError),
    )
    .pipe(
      postcss([
        autoprefixer({
          cascade: false,
        }),
      ]),
    )
    .pipe(
      rename({
        suffix: ".min",
      }),
    )
    .pipe(gulp.dest(paths.styles.dest_min))
    .pipe(browser_sync.stream());
}

export function scripts() {
  return gulp
    .src(paths.scripts.src, { sourcemaps: true })
    .pipe(babel())
    .pipe(uglify())
    .pipe(concat("main.min.js"))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(browser_sync.stream());
}

export function images() {
  return gulp
    .src(paths.img.src, { encoding: false })
    .pipe(webp())
    .pipe(gulp.dest(paths.img.dest));
}

export function browserSync() {
  browser_sync.init({
    server: {
      baseDir: "./",
    },
  });
}

export function watch() {
  gulp.watch(paths.styles.src, styles);
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.img.src, images);
  gulp
    .watch(["*.html", "*.php", "./**/*.php"])
    .on("change", browser_sync.reload);
}

const build = gulp.parallel(watch, browserSync, styles, scripts, images);

export default build;
