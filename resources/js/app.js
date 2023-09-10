import './bootstrap';
import '../css/app.css';


import "mdb-vue-ui-kit/css/mdb.min.css";
import 'mdb-vue-tree-view/css/mdb-vue-tree-view.min.css';
import 'mdb-vue-multi-carousel/css/mdb-vue-multi-carousel.min.css';
import 'mdb-vue-filters/css/mdb-vue-filters.min.css';
import 'mdb-vue-wysiwyg-editor/css/mdb-vue-wysiwyg-editor.min.css';
import 'mdb-vue-transfer/css/mdb-vue-transfer.min.css';
import 'mdb-vue-storage/css/mdb-vue-storage.min.css';
import 'mdb-vue-mention/css/mdb-vue-mention.min.css';
import 'mdb-vue-onboarding/css/mdb-vue-onboarding.min.css';
import 'mdb-vue-data-parser/css/mdb-vue-data-parser.min.css';
import 'mdb-vue-parallax/css/mdb-vue-parallax.min.css';
import 'mdb-vue-organization-chart/css/mdb-vue-organization-chart.min.css';
import 'mdb-vue-captcha/css/mdb-vue-captcha.min.css';
import 'mdb-vue-file-upload/css/mdb-vue-file-upload.min.css';
import 'mdb-vue-calendar/css/mdb-vue-calendar.min.css';
import 'mdb-vue-cookies/css/mdb-vue-cookies.min.css';
import 'mdb-vue-dummy/css/mdb-vue-dummy.min.css';
import 'mdb-vue-input-mask/css/mdb-vue-input-mask.min.css';
import 'mdb-vue-scroll-status/css/mdb-vue-scroll-status.min.css';
import 'mdb-vue-table-editor/css/mdb-vue-table-editor.min.css';
import 'mdb-vue-color-picker/css/mdb-vue-color-picker.min.css';
import 'mdb-vue-treetable/css/mdb-vue-treetable.min.css';
import 'mdb-vue-countdown/css/mdb-vue-countdown.min.css';
import 'mdb-vue-drag-and-drop/css/mdb-vue-drag-and-drop.min.css';
import 'mdb-vue-vector-maps/css/mdb-vue-vector-maps.min.css';
import 'mdb-vue-ecommerce-gallery/css/mdb-vue-ecommerce-gallery.min.css';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Permissions from './Plugins/Permissions';
import VueGates from 'vue-gates';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    progress: {
        color: '#4B5563',
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueGates)
            .use(Permissions)
            .use(ZiggyVue, Ziggy)
            .use(VueSweetalert2)
            .mount(el);
    },
});
