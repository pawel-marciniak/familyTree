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
                                <h5 class="modal-title">{{ fullName }} - descendants</h5>
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
                                <h4>Descendants tree:</h4>
                                <PersonDescendantsTree v-if="person"
                                                       :person="person"
                                />

                                <div class="mt-4" v-if="youngestPerson">
                                    <h4>Youngest descendant:</h4>
                                    {{ youngestPerson.name }} {{ youngestPerson.surname }}
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-secondary"
                                        data-dismiss="modal"
                                        @click="closeModal()"
                                >
                                    Close
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
    import { ref, computed, watch } from 'vue';
    import PersonDescendantsTree from './PersonDescendantsTree';

    export default {
        name: 'PersonDescendantsModal',

        components: { PersonDescendantsTree },

        props: {
            opened: {
                type: Boolean,
                default: false,
            },
            person: {
                type: Object,
                default: () => {},
            },
        },

        setup(props, context) {
            const youngestPerson = ref(null);
            const fullName = computed(() => {
                if (props.person) {
                    return `${props.person.name} ${props.person.surname}`
                }

                return '';
            });

            const closeModal = () => {
                context.emit('close');
            };

            const findYoungest = (children) => {
                let youngest = null;

                children.forEach((child) => {
                    let childYoungest = findYoungest(child.children);

                    if (childYoungest && (youngest === null || childYoungest.birthdate < youngest.birthdate)) {
                        youngest = childYoungest;
                    } else if (youngest === null || child.birthdate < youngest.birthdate) {
                        youngest = child;
                    }
                });

                return youngest;
            };

            watch(() => props.person, () => {
                youngestPerson.value = props.person ? findYoungest(props.person.children) : null;
            })

            return {
                fullName,
                closeModal,
                youngestPerson,
            }
        }
    }
</script>
