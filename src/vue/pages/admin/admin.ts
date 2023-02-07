import AppAdmin from '@/pages/admin/AppAdmin.vue';
import '@/scss/main.scss';
import 'bootstrap';
import {createApp} from 'vue';

const app = createApp(AppAdmin);

app.mount('#app');
