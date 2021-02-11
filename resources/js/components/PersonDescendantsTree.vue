<template>
    <div class="text-left">
        <span class="depth-separator"
              :style="{ width: 10 * depth + 'px' }"
        />
        <i class="fas" :class="[person.gender === 'male' ? 'fa-mars' : 'fa-venus']" />
        {{ fullName }}
        ({{ person.birthdate }})

        <template v-if="person.children && person.children.length > 0">
            <PersonDescendantsTree v-for="child in person.children"
                                   :key="child.id"
                                   :person="child"
                                   :depth="depth + 1"
            />
        </template>
    </div>
</template>

<script>
    import { computed } from 'vue';

    export default {
        name: 'PersonDescendantsTree',

        props: {
            person: {
                type: Object,
                default: () => {},
            },
            depth: {
                type: Number,
                default: 0,
            }
        },

        setup(props) {
            const fullName = computed(() => {
                if (props.person) {
                    return `${props.person.name} ${props.person.surname}`
                }

                return '';
            });

            return {
                fullName,
            }
        }
    }
</script>

<style lang="scss">
    .depth-separator {
        display: inline-block;
        width: 10px;
        position: relative;

        &::after {
            position: absolute;
            top:50px;
            width: 100%;
            border-top: 4px solid grey;
        }
    }
</style>
