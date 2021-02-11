import { createStore } from 'vuex';

const store = createStore({
    state () {
        return {
            user: null,
            descendantsModal: false,
            descendantsModalPerson: null,
        }
    },
    mutations: {
        setUser (state, user) {
            state.user = user;
        },
        showDescendants (state, person) {
            state.descendantsModal = true;
            state.descendantsModalPerson = person;
        },
        hideDescendants (state) {
            state.descendantsModal = false;
            state.descendantsModalPerson = null;
        }
    },
});

export default store;
