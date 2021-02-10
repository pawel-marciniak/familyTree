<template>
    <transition name="fade">
        <div v-if="opened"
             tabindex="-1"
             aria-hidden="true"
        >
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Person</h5>
                                <button type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                        @click="closeModal()"
                                >
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="personGender">Person gender</label>
                                    <select v-model="personGender"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.personGender }"
                                            id="personGender"
                                            name="personGender"
                                    >
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>

                                    <span v-if="errors.personGender"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ errors.personGender }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="personName">Person name</label>
                                    <input v-model="personName"
                                           type="text"
                                           class="form-control"
                                           :class="{ 'is-invalid': errors.personName }"
                                           id="personName"
                                           name="personName"
                                    >

                                    <span v-if="errors.personName"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ errors.personName }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="personSurname">Person surname</label>
                                    <input v-model="personSurname"
                                           type="text"
                                           class="form-control"
                                           :class="{ 'is-invalid': errors.personSurname }"
                                           id="personSurname"
                                           name="personSurname"
                                    >

                                    <span v-if="errors.personSurname"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ errors.personSurname }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="personBirthdate">Person birthdate</label>
                                    <input v-model="personBirthdate"
                                           type="date"
                                           class="form-control"
                                           :class="{ 'is-invalid': errors.personBirthdate }"
                                           id="personBirthdate"
                                           name="personBirthdate"
                                    >

                                    <span v-if="errors.personBirthdate"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ errors.personBirthdate }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-secondary"
                                        data-dismiss="modal"
                                        @click="closeModal()"
                                >
                                    Cancel
                                </button>
                                <button type="button"
                                        class="btn btn-primary"
                                        @click="savePerson()"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import { watch } from 'vue';
    import * as yup from 'yup';
    import { useField, useForm } from 'vee-validate';

    export default {
        name: 'PersonModal',

        props: {
            opened: {
                type: Boolean,
                default: false,
            },
            person: {
                type: Object,
                default: () => {},
            },
            parentPerson: {
                type: Object,
                default: () => {},
            },
            personType: {
                type: String,
                default: 'child',
                validator(value) {
                    return ['child', 'partner'].indexOf(value) !== -1
                }
            }
        },

        setup(props, context) {
            const validationSchema = yup.object({
                personGender: yup.string().required().oneOf(['male', 'female']).label('Person gender'),
                personName: yup.string().required().label('Person name'),
                personSurname: yup.string().required().label('Person surname'),
                personBirthdate: yup.string().required().label('Person birthdate'),
            });

            const { errors, handleSubmit, resetForm } = useForm({
                validationSchema,
            });

            const { value: personGender } = useField('personGender');
            const { value: personName } = useField('personName');
            const { value: personSurname } = useField('personSurname');
            const { value: personBirthdate } = useField('personBirthdate');

            const closeModal = () => {
                context.emit('close');
            };

            const savePerson = handleSubmit(async (values) => {
                let personData = {
                    gender: values.personGender,
                    name: values.personName,
                    surname: values.personSurname,
                    birthdate: values.personBirthdate.split('.').reverse().join('-'),
                };

                if (props.personType === 'partner') {
                    personData.partner_id = props.parentPerson.id;
                } else if(props.personType === 'child') {
                    personData.parent_id = props.parentPerson.id;
                }

                const { data } = await axios.post('/api/persons', personData);

                context.emit('saved', data.data)
            });

            watch(() => props.opened, () => {
                if (props?.person?.id) {
                    personGender.value = props.person.gender;
                    personName.value = props.person.name;
                    personSurname.value = props.person.surname;
                    personBirthdate.value = props.person.birthdate;
                } else {
                    personGender.value = '';
                    personName.value = '';
                    personSurname.value = '';
                    personBirthdate.value = '';

                    resetForm();
                }
            });

            return {
                closeModal,
                personGender,
                personName,
                personSurname,
                personBirthdate,
                errors,
                savePerson,
            }
        }
    }
</script>
