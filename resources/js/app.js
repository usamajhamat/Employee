import './bootstrap';
import { createApp } from 'vue';
import 'flowbite';
import App from './App.vue';
import store from './config/store';
import router from './config/router';
import Vue3Toasity from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import print from 'vue3-print-nb'
import VueSocialSharing from 'vue-social-sharing'

const app = createApp(App)

app.use(store);
app.use(router);
app.use(
    Vue3Toasity,
    {
        autoClose: 3000,
    }
)
app.use(print);
app.use(VueSocialSharing);
app.mount('#app');