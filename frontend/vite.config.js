import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ mode }) => {
  // Check if demo mode is enabled
  const isDemoMode = process.env.VITE_DEMO_MODE !== 'false'
  
  return {
    plugins: [vue()],
    server: {
      port: 3001,
      host: '0.0.0.0',
      strictPort: true, // Force use of port 3001, fail if not available
      // Only setup proxy if NOT in demo mode
      proxy: isDemoMode ? {} : {
        '/api': {
          target: process.env.VITE_API_URL || 'http://localhost:8000',
          changeOrigin: true,
          rewrite: (path) => {
            // For local development, remove /api prefix
            if (process.env.NODE_ENV !== 'production') {
              const newPath = path.replace(/^\/api/, '');
              console.log('Rewriting path:', path, '->', newPath || '/');
              return newPath || '/';
            }
            // For production, keep /api for Netlify Functions
            return path;
          },
          configure: (proxy, options) => {
            proxy.on('error', (err, req, res) => {
              console.log('Proxy error:', err);
            });
            proxy.on('proxyReq', (proxyReq, req, res) => {
              console.log('Proxy request:', req.method, req.url, '->', proxyReq.path);
            });
          }
        }
      }
    },
    resolve: {
      alias: {
        '@': '/src'
      }
    },
    build: {
      outDir: 'dist',
      assetsDir: 'assets',
      sourcemap: false,
      minify: 'terser',
      rollupOptions: {
        output: {
          manualChunks: {
            vendor: ['vue', 'vue-router', 'pinia'],
            ui: ['bootstrap', '@popperjs/core']
          }
        }
      }
    },
    base: './', // Relative paths for deployment
    define: {
      'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
    }
  }
})