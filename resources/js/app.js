import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import PrimeVue from 'primevue/config'
import Aura from '@primevue/themes/aura'
import Nora from '@primevue/themes/nora'
import Lara from '@primevue/themes/lara'
import {ZiggyVue} from 'ziggy-js'


createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                theme: {
                    preset: Lara
                }
            })
            .use(ZiggyVue)
            .mount(el)
    },
})
