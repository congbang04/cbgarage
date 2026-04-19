import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import { globSync } from "glob";

export default defineConfig(({ command, mode }) => {
    const moduleScripts = globSync("resources/js/modules/**/*.js");

    const inputFiles = [
        "resources/css/app.css",
        "resources/js/app.js",
        ...(command === "build" ? moduleScripts : []),
    ];

    return {
        plugins: [
            laravel({
                input: inputFiles,
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            host: "0.0.0.0",
            port: 5175,
            hmr: {
                host: "localhost",
                clientPort: 5175,
            },
            watch: {
                usePolling: true,
                interval: 1000,
            },
        },
    };
});
