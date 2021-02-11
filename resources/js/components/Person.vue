<template>
    <div class="person" :class="[genderClass]">
        <slot></slot>

        <img src="/img/avatar.png" class="person__img rounded" :alt="fullName">

        <div>{{ fullName }}</div>
        <div>{{ person?.birthdate }}</div>
    </div>
</template>

<script>
    import { computed } from 'vue';
    import { useStore } from 'vuex';
    import PersonDescendantsModal from './PersonDescendantsModal';

    export default {
        name: 'Person',

        components: { PersonDescendantsModal },

        props: {
            person: {
                type: Object,
                default: () => {},
            }
        },

        emits: ['add-partner', 'add-child'],

        setup(props) {
            const fullName = computed(() => {
                if (props.person) {
                    return `${props.person.name} ${props.person.surname}`
                }

                return '';
            });

            const genderClass = computed(() => {
                return props.person.gender === 'male' ? 'person--male' : 'person--female';
            });

            return {
                fullName,
                genderClass,
            }
        }
    }
</script>

<style lang="scss">
    .person {
        text-align: center;
        padding: 1rem;
        margin: 1rem;
        width: 10rem;
        height: 10rem;
        position: relative;

        .person__descendants {
            position: absolute;
            top: 5px;
            right: 5px;
            color: white;
        }

        &.person--male {
            background-color: #add8e6;
        }

        &.person--female {
            background-color: lightpink;
        }

        .person__img {
            width: 50px;
        }
    }
</style>
