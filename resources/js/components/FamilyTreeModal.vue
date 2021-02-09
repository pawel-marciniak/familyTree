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
                                <h5 class="modal-title">Family tree</h5>
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
                                    <label for="familyName">Family name</label>
                                    <input v-model="familyName"
                                           type="text"
                                           class="form-control"
                                           :class="{ 'is-invalid': errors.familyName }"
                                           id="familyName"
                                           name="familyName"
                                    >

                                    <span v-if="errors.familyName"
                                          class="invalid-feedback"
                                          role="alert"
                                    >
                                        <strong>{{ errors.familyName }}</strong>
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
                                        @click="saveFamily()"
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
        name: 'FamilyTreeModal',

        props: {
            opened: {
                type: Boolean,
                default: false,
            },
            familyTree: {
                type: Object,
                default: () => {},
            }
        },

        setup(props, context) {
            const validationSchema = yup.object({
                familyName: yup.string().required().label('Family name'),
                personName: yup.string().required().label('Person name'),
                personSurname: yup.string().required().label('Person surname'),
                personBirthdate: yup.string().required().label('Person birthdate'),
            });

            const { errors, handleSubmit, resetForm } = useForm({
                validationSchema,
            });

            const { value: familyName } = useField('familyName');
            const { value: personName } = useField('personName');
            const { value: personSurname } = useField('personSurname');
            const { value: personBirthdate } = useField('personBirthdate');

            const closeModal = () => {
                context.emit('close');
            };

            const saveFamily = handleSubmit(async (values) => {
                const { data } = await axios.post('/api/family-trees', {
                    name: values.familyName,
                    personName: values.personName,
                    personSurname: values.personSurname,
                    personBirthdate: values.personBirthdate.split('.').reverse().join('-'),
                });

                context.emit('saved', data.data)
            });

            watch(() => props.opened, () => {
                if (props?.familyTree?.id) {
                    familyName.value = props.familyTree.name;
                    personName.value = props.familyTree.headPerson.name;
                    personSurname.value = props.familyTree.headPerson.surname;
                    personBirthdate.value = props.familyTree.headPerson.birthdate;
                } else {
                    familyName.value = '';
                    personName.value = '';
                    personSurname.value = '';
                    personBirthdate.value = '';

                    resetForm();
                }
            });

            return {
                closeModal,
                familyName,
                personName,
                personSurname,
                personBirthdate,
                errors,
                saveFamily,
            }
        }
    }
</script>
