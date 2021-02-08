<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                FamilyTree
            </a>
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
                            <a class="dropdown-item" @click="logout()">
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
            const user = computed(() => store.getters.loggedUser);

            const logout = async () => {
                await axios.post('/api/logout');

                store.commit('setUser', null);
                localStorage.removeItem('user');

                await router.push({ name: 'login' });
            };

            return {
                logout,
                user,
            };
        }
    }
</script>
