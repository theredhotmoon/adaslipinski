import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { VueQueryPlugin } from '@tanstack/vue-query'
import router from '@/router'
import { queryClient } from '@/lib/query-client'
import { i18n } from '@/i18n'
import App from './App.vue'
import './style.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(i18n)
app.use(VueQueryPlugin, { queryClient })

app.mount('#app')
