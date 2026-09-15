import { defineConfig } from 'vite'

// In dev mode, swap the static <link>/<script> refs for the Vite module entry
// so HMR works. In build mode, the static refs are kept as-is.
function devEntrySwap() {
  return {
    name: 'dev-entry-swap',
    transformIndexHtml: {
      order: 'pre',
      handler(html, ctx) {
        if (!ctx.server) return html // build mode — leave HTML unchanged
        return html
          .replace(/<link rel="stylesheet" href="\.\/public\/assets\/main\.css">\s*/g, '')
          .replace(
            /<script defer src="\.\/public\/assets\/main\.js"><\/script>/,
            '<script type="module" src="/src/js/main.js"></script>',
          )
      },
    },
  }
}

export default defineConfig({
  base: './',
  plugins: [devEntrySwap()],
  publicDir: 'public',
  build: {
    outDir: '../../public/assets',
    emptyOutDir: false,       // keep public/assets/images untouched
    cssCodeSplit: false,
    rollupOptions: {
      input: 'src/js/main.js',
      output: {
        format: 'iife',
        entryFileNames: 'main.js',
        chunkFileNames: '[name].js',
        assetFileNames: (info) => {
          if (info.names && info.names.some(n => n.endsWith('.css'))) {
            return 'main.css'
          }
          return '[name][extname]'
        },
      },
    },
  },
  css: {
    preprocessorOptions: {
      scss: { quietDeps: true },
    },
  },
})
