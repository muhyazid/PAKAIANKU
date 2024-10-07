import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",

                // "resources/assets/vendors/mdi/css/materialdesignicons.min.css",
                // "resources/assets/vendors/css/vendor.bundle.base.css",
                // "resources/assets/css/style.css",
            ],
            refresh: true,

            // theeme css
        }),
    ],
});
