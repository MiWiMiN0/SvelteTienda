import { svelte } from '@sveltejs/vite-plugin-svelte'
import { defineConfig } from 'vite'

export default defineConfig({
	plugins: [svelte()],
	server: {
		proxy: {
			'/api': {
				target: 'http://localhost:8000', // Reemplaza con la URL y puerto exacto de tu backend
				changeOrigin: true,
				secure: false,
				rewrite: (path) => path.replace(/^\/api/, '/api') // Ajusta según la estructura de tus rutas
			}
		}
	}
})
