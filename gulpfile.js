import zip from "gulp-zip";
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
import jsonSass from "gulp-json-sass";

const sass = gulpSass(dartSass);
const bs = browser_sync.create();

const dev = "./assets/_dev";

var paths = {
  styles: {
    src: `${dev}/css/scss/**/*.scss`,
    dest: `${dev}/css`,
    dest_min: "./assets/css",
  },
  scripts: {
    src: [`${dev}/scripts/**/*.js`, `!${dev}/scripts/**/_*.js`],
    dest: "./assets/js",
  },
  img: {
    src: `${dev}/img/**/*.{jpg,png,jpeg,tif,tiff}`,
    dest: "./assets/img",
  },
};

export function jsonToSass() {
  return gulp
    .src("./theme-config.json")
    .pipe(jsonSass({ sass: false }))
    .pipe(rename("_json-vars.scss"))
    .pipe(gulp.dest(`${dev}/css/scss/config`));
}

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
    .pipe(bs.stream());
}

export function scripts() {
  return gulp
    .src(paths.scripts.src, { sourcemaps: false })
    .pipe(babel())
    .pipe(uglify())
    .pipe(concat("main.min.js"))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(bs.stream());
}

export function images() {
  return gulp
    .src(paths.img.src, { encoding: false })
    .pipe(webp())
    .pipe(gulp.dest(paths.img.dest));
}

export function browserSync() {
  bs.init({
    proxy: "rdg.test",
  });
}

export function exportZip() {
  return gulp
    .src([
      "./**/*",
      "!./node_modules/**",
      "!./assets/_dev/**",
      "!./.git/**",
      "!./.gitignore",
      "!./gulpfile.js",
      "!./package*.json",
      "!./composer.lock",
      "!./*.zip",
    ])
    .pipe(zip("tema-producao.zip"))
    .pipe(gulp.dest("../"));
}

export function watch() {
  gulp.watch(paths.styles.src, styles);
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.img.src, images);

  gulp.watch("./theme-config.json", gulp.series(jsonToSass, styles));
  gulp.watch(["*.html", "*.php", "./**/*.php"]).on("change", bs.reload);
}

export const build = gulp.series(
  jsonToSass,
  gulp.parallel(styles, scripts, images),
  exportZip,
);

const devTask = gulp.series(
  jsonToSass,
  gulp.parallel(styles, scripts, images),
  gulp.parallel(watch, browserSync),
);

export default devTask;
