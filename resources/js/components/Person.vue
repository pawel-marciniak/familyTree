<template>
    <div class="person" :class="[genderClass]">
        <img src="/img/avatar.png" class="person__img rounded" :alt="fullName">

        <div>{{ fullName }}</div>
        <div>{{ person?.birthdate }}</div>
    </div>
</template>

<script>
    import { computed } from 'vue';

    export default {
        name: 'Person',

        props: {
            person: {
                type: Object,
                default: () => {},
            }
        },

        emits: ['add-partner', 'add-child'],

        setup(props, context) {
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
