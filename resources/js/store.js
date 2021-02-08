import { createStore } from 'vuex';

const store = createStore({
    state () {
        return {
            user: null,
        }
    },
    mutations: {
        setUser (state, user) {
            state.user = user;
        }
    },
    getters: {
        loggedUser (state) {
            if (state.user) {
                return state.user;
            } else if (localStorage.getItem('user')) {
                return JSON.parse(localStorage.getItem('user'));
            }

            return null;
        }
    }
});

export default store;
