<template>
    <div class="person-tree">
        <div :class="{ 'person-tree__couple': localPerson.partner }">
            <Person :person="localPerson"
                    class="person--child"
            >
                <button class="btn btn-info btn-sm person__descendants"
                        @click="showDescendants(localPerson)"
                >
                    <i class="fas fa-info-circle" />
                </button>
            </Person>

            <Person v-if="localPerson.partner"
                    :person="localPerson.partner"
                    class="person--partner"
            />
        </div>

        <div class="dropdown">
            <button class="btn btn-sm btn-success person-tree__add-btn" data-toggle="dropdown">
                <i class="fas fa-plus"></i>
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a v-if="!localPerson.partner"
                   href="#"
                   class="dropdown-item"
                   @click.prevent="addPerson('partner')"
                >
                    Partner
                </a>

                <a href="#"
                   class="dropdown-item"
                   @click.prevent="addPerson('child')"
                >
                    Child
                </a>
            </div>
        </div>

        <div v-if="localPerson.children && localPerson.children.length > 0"
             class="person-tree__children"
        >
            <PersonTree v-for="child in localPerson.children"
                        :key="child.id"
                        :person="child"
            />
        </div>

        <PersonModal :opened="personModal"
                     :person-type="personType"
                     :parent-person="localPerson"
                     @close="personModal = false"
                     @saved="savePerson"
        />
    </div>
</template>

<script>
    import { ref } from 'vue';
    import Person from './Person';
    import PersonModal from './PersonModal';
    import { useStore } from 'vuex';

    export default {
        name: 'PersonTree',

        components: { Person, PersonModal },

        props: {
            person: {
                type: Object,
                default: () => {},
            }
        },

        setup(props) {
            const personModal = ref(false);
            const personType = ref('child');
            const localPerson = ref({});
            const store = useStore();

            localPerson.value = props.person;

            const addPerson = (type) => {
                personType.value = type;
                personModal.value = true;
            };

            const savePerson = (personData) => {
                if (personType.value === 'partner') {
                    localPerson.value.partner = personData;
                } else if (personType.value === 'child') {
                    localPerson.value.children.push(personData);
                }

                personModal.value = false;
            };

            const showDescendants = (person) => {
                store.commit('showDescendants', person);
            };

            return {
                personModal,
                personType,
                localPerson,
                addPerson,
                savePerson,
                showDescendants,
            }
        }
    }
</script>

<style lang="scss">
    .person-tree {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: min-content;

        .person-tree__couple {
            display: flex;
            flex-direction: row;
            position: relative;

            &::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 50%;
                border-left: 4px solid #ccc;
                width: 0;
                height: 5rem;
            }

            .person--partner {
                position: relative;

                &::after {
                    content: "";
                    position: absolute;
                    left: -2rem;
                    top: 50%;
                    border-bottom: 4px solid #ccc;
                    width: 2rem;
                    height: 1rem;
                }
            }
        }

        .person-tree__children {
            display: flex;
            flex-direction: row;
            position: relative;

            &::after {
                content: "";
                position: absolute;
                left: 6rem;
                top: -2rem;
                border-bottom: 4px solid #ccc;
                width: calc(100% - 12rem);
                height: 1rem;
            }

            .person--child {
                position: relative;

                &::before {
                    content: "";
                    position: absolute;
                    top: -2rem;
                    left: 50%;
                    border-left: 4px solid #ccc;
                    width: 0;
                    height: 2rem;
                }
            }
        }

        .person-tree__add-btn {
            position: relative;
            z-index: 2;
        }
    }
</style>
