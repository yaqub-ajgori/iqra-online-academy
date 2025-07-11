import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'autoUpdate',
            includeAssets: ['favicon.svg', 'robots.txt', 'apple-touch-icon.png'],
            manifest: {
                name: 'ইকরা অনলাইন একাডেমি',
                short_name: 'ইকরা',
                description: 'ইসলামিক শিক্ষার জন্য আধুনিক অনলাইন একাডেমি',
                theme_color: '#5f5fcd',
                background_color: '#ffffff',
                display: 'standalone',
                start_url: '/',
                scope: '/',
                lang: 'bn',
                dir: 'ltr',
                orientation: 'portrait-primary',
                categories: ['education', 'lifestyle', 'utilities'],
                icons: [
                    {
                        src: '/icons/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'any maskable',
                    },
                    {
                        src: '/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any maskable',
                    },
                ],
                screenshots: [
                    {
                        src: '/icons/screenshot-mobile.png',
                        sizes: '390x844',
                        type: 'image/png',
                        form_factor: 'narrow',
                        label: 'ইকরা অনলাইন একাডেমি'
                    }
                ]
            },
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg,webp,jpg,jpeg}'],
                navigateFallback: null,
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/fonts\.(googleapis|bunny\.net)\//,
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'google-fonts',
                            expiration: {
                                maxEntries: 4,
                                maxAgeSeconds: 365 * 24 * 60 * 60 // 1 year
                            }
                        }
                    },
                    {
                        urlPattern: /^https:\/\/images\.unsplash\.com\//,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'unsplash-images',
                            expiration: {
                                maxEntries: 50,
                                maxAgeSeconds: 30 * 24 * 60 * 60 // 30 days
                            }
                        }
                    }
                ]
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
});
