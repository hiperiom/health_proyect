import './bootstrap';
import './echo';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import Antd from 'ant-design-vue';

import '../css/ant-design-vue/dist/antd.css';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/es'; 

dayjs.extend(relativeTime);
dayjs.locale('es'); // Configurar español por defecto
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: () => `${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // 3. Inyectar Day.js globalmente
        app.config.globalProperties.$date = dayjs;

        return app
            .use(plugin)
            .use(ZiggyVue)
            .use(Antd)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
