import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import { globSync } from "glob";
import path from "path";

export default () => {
    const jsFiles = globSync(["resources/ts/**/[aA]pp.ts"], { absolute: true });

    return defineConfig({
        plugins: [
            laravel({
                input: jsFiles,
                refresh: true,
            }),
            // 只針對 core-ui-admin 啟用 React Plugin
            react(),
        ],
        resolve: {
            alias: {
                "@core-ui-admin": path.resolve(
                    __dirname,
                    "resources/ts/core-ui-admin"
                ),
                "@lezada": path.resolve(__dirname, "resources/ts/lezada"),
            },
        },
    });
};
