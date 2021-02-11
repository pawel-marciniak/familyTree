<link rel="stylesheet" href="../../../../Desktop/_variables.scss">
<template>
    <nav class="navbar navbar-expand-md navbar-light shadow-sm">
        <div class="container">
            <router-link class="navbar-brand" :to="{ name: 'home' }">
                FamilyTree
            </router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="lelele">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <li v-if="!user" class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'login' }">Login</router-link>
                    </li>

                    <li v-if="!user" class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'register' }">Register</router-link>
                    </li>

                    <li class="nav-item dropdown"
                    >
                        <a v-if="user"
                           id="navbarDropdown"
                           class="nav-link dropdown-toggle"
                           href="#" role="button"
                           data-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false"
                        >
                            {{ user.name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a href="#" class="dropdown-item" @click.prevent="logout()">
                               Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <router-view />
    </main>
</template>

<script>
    import { computed } from 'vue';
    import { useRouter } from 'vue-router';
    import { useStore } from 'vuex';

    export default {
        name: 'App',

        setup() {
            const router = useRouter();
            const store = useStore();
            const user = computed(() => {
                if (store.state.user) {
                    return store.state.user;
                } else if (localStorage.getItem('user')) {
                    return JSON.parse(localStorage.getItem('user'));
                }
            });

            const logout = async () => {
                await axios.post('/api/logout');

                localStorage.removeItem('user');
                store.commit('setUser', null);

                await router.push({ name: 'login' });
            };

            return {
                logout,
                user,
            };
        }
    }
</script>
