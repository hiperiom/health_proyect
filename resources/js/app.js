import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// 1. Importar Ant Design Vue
import Antd from 'ant-design-vue';

// 2. Importar el archivo de estilos principal (necesario)
// Nota: Puedes elegir entre 'antd.css' o 'antd.less' si personalizas el tema.
import '../css/ant-design-vue/dist/antd.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Antd)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
