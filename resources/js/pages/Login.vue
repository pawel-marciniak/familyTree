<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form @submit.prevent="submitLogin()">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                                <div class="col-md-6">
                                    <input v-model="email"
                                           id="email"
                                           type="email"
                                           class="form-control"
                                           :class="{ 'is-invalid': emailError }"
                                           name="email"
                                           required
                                           autocomplete="email"
                                           autofocus
                                    >

                                    <span v-if="emailError"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ emailError }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input v-model="password"
                                           id="password"
                                           type="password"
                                           class="form-control"
                                           :class="{ 'is-invalid': passwordError }"
                                           name="password"
                                           required
                                           autocomplete="current-password"
                                    >

                                    <span v-if="passwordError"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ passwordError }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit"
                                            class="btn btn-primary"
                                    >
                                        Login
                                    </button>

                                    <div v-if="serverError"
                                          class="text-danger"
                                          role="alert"
                                    >
                                        <strong>{{ serverError }}</strong>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';
    import { useForm, useField } from 'vee-validate';
    import * as yup from 'yup';
    import { useStore } from 'vuex'

    export default {
        name: 'Login',

        setup() {
            const serverError = ref(null);
            const router = useRouter();
            const store = useStore();

            const validationSchema = yup.object({
                email: yup.string().required().email(),
                password: yup.string().required().min(8),
            });

            const { meta: formMeta } = useForm({
                validationSchema,
            });

            const { value: email, errorMessage: emailError } = useField('email');
            const { value: password, errorMessage: passwordError } = useField('password');

            const submitLogin = async () => {
                if (formMeta.value.valid) {
                    await axios.get('/sanctum/csrf-cookie');

                    try {
                        await axios.post('/api/login', {
                            email: email.value,
                            password: password.value,
                        });

                        const { data } = await axios.get('/api/user');

                        store.commit('setUser', data);
                        localStorage.setItem('user', JSON.stringify(data));

                        await router.push({
                            name: 'home',
                        });
                    } catch (error) {
                        serverError.value = error.response.data.message;
                    }
                }
            };

            return {
                email,
                emailError,
                password,
                passwordError,
                serverError,
                submitLogin,
            };
        }
    }
</script>
