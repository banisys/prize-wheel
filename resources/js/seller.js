import './bootstrap'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import "vuetify/styles"
import { createVuetify } from "vuetify"
import * as components from "vuetify/components"
import * as directives from "vuetify/directives"

const vuetify = createVuetify({
    components,
    directives
});


createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('../../pages/sellers/**/*.vue', { eager: true })
    return pages[`../../pages/sellers/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({
      render: () => h(App, props),
      data: () => ({
        baseUrl: import.meta.env.VITE_APP_BASE_URL,
        assetsUrl: import.meta.env.VITE_APP_ASSETS_URL,
        apiUrl: import.meta.env.VITE_APP_API_URL,
      }),
    }).use(plugin).use(vuetify).mount(el)
  }
})
