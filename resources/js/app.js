import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import './bootstrap';
import '../css/app.css';

const app = createApp(App);

app.config.globalProperties.$formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-EN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

app.use(router);
app.mount('#app');