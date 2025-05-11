// packages/pinpin/themes-lezada/gulpfile.cjs

const gulp = require("gulp");
const sassImpl = require("sass");
const gulpSass = require("gulp-sass")(sassImpl);
const cleanCSS = require("gulp-clean-css");
const fs = require("fs");
const path = require("path");

// 因為 Node.js require 不支援直接引入 ESM module
// 所以如果 helper.js 是用 import export，這裡要改動或另寫一版 CommonJS
// 假設這裡直接簡化 isFile 檢查
const isFile = (filePath) => {
    try {
        return fs.statSync(filePath).isFile();
    } catch (err) {
        return false;
    }
};

// TODO: 如果 fetchDataFromDB 很複雜，需要重新寫成 CommonJS 模組；這裡先 mock 一個簡版
const fetchDataFromDB = async (themeSlug) => {
    let sassCollect = [`packages/pinpin/themes/${themeSlug}/resources/sass/app.scss`];
    const bootstrapPath = path.resolve(
        __dirname,
        `packages/pinpin/themes/${themeSlug}/resources/sass/bootstrap5.scss`
    );

    if (fs.existsSync(bootstrapPath)) {
        sassCollect.push(bootstrapPath);
    }
    return [
        {
            config_json: {
                gulp: {
                    sass: sassCollect, 
                    //["resources/sass/app.scss", "resources/sass/bootstrap5.scss"],
                },
            },
        },
    ];
};

// 基本設定
const themeSlug = "pinpin/themes-lezada";
const saveFolder = themeSlug.split('/')[1].replace(/^themes\-/, '');

const saveCssFolder = path.resolve(
    __dirname,
    "public/themes/" + saveFolder + "/css"
);
const saveJsFolder = path.resolve(
    __dirname,
    "public/themes/" + saveFolder + "/js"
);

// --- SASS 編譯任務 ---
gulp.task("sass", async (cb) => {
    const rows = await fetchDataFromDB(themeSlug);
    const sassPath = rows[0].config_json.gulp.sass;

    if (!fs.existsSync(saveCssFolder)) {
        fs.mkdirSync(saveCssFolder, { recursive: true });
    }

    if (
        typeof sassPath === "string" &&
        isFile(path.resolve(__dirname, sassPath))
    ) {
        gulp.src(path.resolve(__dirname, sassPath))
            .pipe(gulpSass().on("error", gulpSass.logError))
            .pipe(cleanCSS())
            .pipe(gulp.dest(saveCssFolder));
    }

    if (Array.isArray(sassPath)) {
        const validPaths = sassPath
            .map((p) => path.resolve(__dirname, p))
            .filter(isFile);
        if (validPaths.length > 0) {
            gulp.src(validPaths)
                .pipe(gulpSass().on("error", gulpSass.logError))
                .pipe(cleanCSS())
                .pipe(gulp.dest(saveCssFolder));
        }
    }

    cb();
});

// --- Bootstrap JS 複製任務 ---
gulp.task("copy-bootstrap", (cb) => {
    if (!fs.existsSync(saveJsFolder)) {
        fs.mkdirSync(saveJsFolder, { recursive: true });
    }

    const bootstrapJs = path.resolve(
        __dirname,
        "./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"
    );
    const bootstrapMap = path.resolve(
        __dirname,
        "./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map"
    );

    if (isFile(bootstrapJs)) {
        fs.copyFileSync(
            bootstrapJs,
            path.resolve(saveJsFolder, "bootstrap.bundle.min.js")
        );
    }
    if (isFile(bootstrapMap)) {
        fs.copyFileSync(
            bootstrapMap,
            path.resolve(saveJsFolder, "bootstrap.bundle.min.js.map")
        );
    }
    cb();
});

// --- Sass Watch 任務 ---
gulp.task("watch", async () => {
    const rows = await fetchDataFromDB();
    const sassPath = rows[0].config_json.gulp.sass;

    if (typeof sassPath === "string" || Array.isArray(sassPath)) {
        const watchPaths = Array.isArray(sassPath) ? sassPath : [sassPath];
        const fullPaths = watchPaths.map((p) => path.resolve(__dirname, p));
        gulp.watch(fullPaths, gulp.series("sass"));
    }
});

// --- Default 任務串接 ---
gulp.task("default", gulp.series("sass", "copy-bootstrap", "watch"));

// --- All frontend 任務
gulp.task("all-frontend", gulp.series("sass", "copy-bootstrap"));