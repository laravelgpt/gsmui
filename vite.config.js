
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
    },
    server: {
        hmr: {
            host: 'localhost',
        }
    },
});
