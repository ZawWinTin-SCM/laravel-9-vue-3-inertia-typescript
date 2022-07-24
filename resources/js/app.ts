import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'

import '../sass/app.scss'
import 'bootstrap'
import 'bootstrap-icons/font/bootstrap-icons.css'

createInertiaApp({
    resolve: name => import(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
            .use(plugin)
            .mount(el)
    },
})
InertiaProgress.init()