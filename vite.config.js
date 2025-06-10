import { defineConfig } from 'vite'
import symfonyPlugin from 'vite-plugin-symfony'

export default defineConfig(({ mode }) => ({
  plugins: [symfonyPlugin()],
  root: 'assets',          // la racine de tes sources est 'assets'
  base: mode === 'production' ? '/build/' : '/',
  build: {
    outDir: '../public/build',  // build en prod vers public/build (un niveau au dessus)
    emptyOutDir: true,
    rollupOptions: {
      input: {
        app: 'js/app.js',        // chemin relatif dans assets/
      },
    },
  },
  server: {
    port: 5173,
    strictPort: true,
  },
}))
