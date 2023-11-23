import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import  VueGoogleMaps from '@fawmi/vue-google-maps'
import deepEqual from 'fast-deep-equal';

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(VueGoogleMaps, {
    load: {
        key: '',
        libraries: "places"
        // language: 'de',
    },
})
app.mount('#app')
