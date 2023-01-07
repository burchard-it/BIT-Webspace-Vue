import {createRouter, createWebHistory} from 'vue-router';
import HomeView from '@/views/HomeView.vue';

const router = createRouter({
  // history: createWebHistory(import.meta.env.BASE_URL),
  // workaround for https://github.com/lhapaipai/vite-bundle/commit/0bbbc4649ec8ed1bfc39b973b2aed043f4dc8c30#commitcomment-95341866
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('@/views/AboutView.vue'),
    },
  ],
});

export default router;
