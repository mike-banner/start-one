import { defineConfig } from 'vite'
import symfonyPlugin from 'vite-plugin-symfony'

export default defineConfig({
  plugins: [
    symfonyPlugin(),
    // react(), // décommente si tu utilises React
  ],
  base: '/build/',
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        app: './assets/app.js',
      },
    },
  },
  server: {
    port: 5173,
    strictPort: true,
  },
})
