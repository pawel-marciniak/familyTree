import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login';
import Register from './pages/Register';
import Home from './pages/Home';

const routes = [
    { path: '/login', component: Login, name: 'login' },
    { path: '/register', component: Register, name: 'register' },
    { path: '/', component: Home, name: 'home' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    let isAuthenticated = true;

    try {
        const { data } = await axios.get('/api/user');

        localStorage.setItem('user', JSON.stringify(data));
    } catch (error) {
        isAuthenticated = false;
    }

    if (to.name !== 'login' && to.name !== 'register' && !isAuthenticated) {
        next({ name: 'login' });
    } else if ((to.name === 'login' || to.name === 'register') && isAuthenticated) {
        next({ name: 'home' });
    }else {
        next();
    }
});

export default router;
