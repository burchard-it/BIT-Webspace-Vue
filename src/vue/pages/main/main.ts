import AppMain from '@/pages/main/AppMain.vue';
import router from '@/pages/main/router';
import '@/scss/main.scss';
import 'bootstrap';
import {createApp} from 'vue';

const app = createApp(AppMain);

app.use(router);

app.mount('#app');
