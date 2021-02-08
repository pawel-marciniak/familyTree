<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form @submit.prevent="submitRegister()">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                <div class="col-md-6">
                                    <input v-model="name"
                                           id="name"
                                           type="text"
                                           class="form-control"
                                           :class="{ 'is-invalid': nameError }"
                                           name="name"
                                           required
                                           autocomplete="name"
                                           autofocus
                                    >

                                    <span v-if="nameError"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ nameError }}</strong>
                                    </span>
                                </div>
                            </div>

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

                            <div class="form-group row">
                                <label for="confirmPassword" class="col-md-4 col-form-label text-md-right">Confirm password</label>

                                <div class="col-md-6">
                                    <input v-model="confirmPassword"
                                           id="confirmPassword"
                                           type="password"
                                           class="form-control"
                                           :class="{ 'is-invalid': confirmPasswordError }"
                                           name="confirmPassword"
                                           required
                                           autocomplete="current-password"
                                    >

                                    <span v-if="confirmPasswordError"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ confirmPasswordError }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit"
                                            class="btn btn-primary"
                                    >
                                        Login
                                    </button>
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
    import { useForm, useField } from 'vee-validate';
    import * as yup from 'yup';
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';

    export default {
        name: 'Register',

        setup() {
            const serverError = ref(null);
            const router = useRouter();

            const schema = yup.object({
                email: yup.string().required().email(),
                password: yup.string().required().min(8),
                confirmPassword: yup.string().required().min(8).oneOf([yup.ref("password")], "Passwords do not match"),
            });

            const { meta: formMeta } = useForm({
                validationSchema: schema,
            });

            const { value: email, errorMessage: emailError } = useField('email');
            const { value: password, errorMessage: passwordError } = useField('password');
            const { value: confirmPassword, errorMessage: confirmPasswordError } = useField('confirmPassword');
            const { value: name, errorMessage: nameError } = useField('name');

            const submitRegister = async () => {
                if (formMeta.value.valid) {
                    await axios.get('/sanctum/csrf-cookie');

                    try {
                        await axios.post('/api/register', {
                            email: email.value,
                            password: password.value,
                            password_confirmation: confirmPassword.value,
                            name: name.value,
                        });

                        await router.push({
                            name: 'login',
                        });
                    } catch (error) {
                        serverError.value = error.response.message;
                    }
                }
            };

            return {
                email,
                emailError,
                password,
                passwordError,
                confirmPassword,
                confirmPasswordError,
                name,
                nameError,
                serverError,
                submitRegister,
            };
        }
    }
</script>
