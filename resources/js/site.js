import './bootstrap'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'


createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('../../pages/site/**/*.vue', { eager: true })
    return pages[`../../pages/site/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({
      render: () => h(App, props),
      data: () => ({
        baseURL: import.meta.env.VITE_APP_BASE_URL,
        assetsURL: import.meta.env.VITE_APP_ASSETS_URL
      }),
    })
      .use(plugin)
      .mount(el)
  }
})
