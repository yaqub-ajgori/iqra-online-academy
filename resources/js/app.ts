import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { registerSW } from 'virtual:pwa-register';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// Register service worker
if ('serviceWorker' in navigator) {
    const updateSW = registerSW({
        onNeedRefresh() {
            // Emit custom event that PWAUpdatePrompt component listens for
            document.dispatchEvent(new CustomEvent('swUpdate', { detail: { updateSW } }));
        },
        onOfflineReady() {
            // Emit custom event that app is ready for offline use
            document.dispatchEvent(new CustomEvent('swOfflineReady'));
        },
    });
}
