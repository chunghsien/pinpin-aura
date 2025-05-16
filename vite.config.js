import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import { globSync } from "glob";
import path from "path";

export default () => {
    const targetApp = process.env.VITE_APP || "**";

    const jsFiles = globSync(
        [
            `resources/ts/themes/${targetApp}/([aA]pp|main).@(ts|tsx)`, // 共用 main.ts + 主程式
            `resources/ts/themes/themes-lezada/pages/*.ts`, // 頁面腳本
        ],
        { absolute: true }
    );

    const isAdmin = jsFiles.some((file) => file.includes("core-ui-admin"));

    const plugins = [
        laravel({
            input: jsFiles,
            refresh: true,
        }),
    ];

    if (isAdmin) {
        plugins.push(
            react({
                jsxImportSource: "react",
                babel: {
                    plugins: ["@babel/plugin-syntax-jsx"],
                },
            })
        );
    }

    return defineConfig({
        plugins,
        resolve: {
            preserveSymlinks: true,
            alias: {
                "@core-ui-admin": path.resolve(
                    __dirname,
                    "resources/ts/themes/core-ui-admin"
                ),
                "@themes-lezada": path.resolve(
                    __dirname,
                    "resources/ts/themes/themes-lezada"
                ),
            },
        },
        build: {
            outDir: "public/build", // ✅ 統一輸出目錄
            manifest: true, // ✅ 開啟 manifest.json
            manifestFileName: "manifest.json", // ✅ 指定 manifest 檔案名稱
            rollupOptions: {
                input: jsFiles,
            },
            emptyOutDir: true,
        },
        server: {
            host: "localhost",
            port: 5173,
            hmr: {
                host: "localhost",
            },
        },
    });
};
