<template>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div v-if="serverError" class="alert alert-danger" role="alert">
                    {{ serverError }}
                </div>

                <h3>Family: {{ familyTreeData?.name }}</h3>

                <div v-if="loading" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <div class="family-tree-container">
                    <person-tree v-if="headPersonTree"
                                 :person="headPersonTree"
                    />
                </div>
            </div>
        </div>

        <PersonDescendantsModal :opened="store.state.descendantsModal"
                                :person="store.state.descendantsModalPerson"
                                @close="store.commit('hideDescendants')"
        />
    </div>
</template>

<script>
    import { ref, watch, onMounted } from 'vue';
    import { useRoute } from 'vue-router';
    import { useStore } from 'vuex';
    import PersonTree from '../components/PersonTree';
    import PersonDescendantsModal from '../components/PersonDescendantsModal';

    export default {
        name: 'FamilyTree',

        components: { PersonTree, PersonDescendantsModal },

        setup() {
            const store = useStore();
            const route = useRoute();
            const familyTreeData = ref(null);
            const headPersonTree = ref(null);
            const serverError = ref(null);
            const loading = ref(false);

            watch(
                () => route.params,
                async newParams => {
                    if (newParams?.id) {
                        await fetchFamilyTree(newParams.id);
                    }
                }
            );

            const fetchFamilyTree = async (familyTreeId) => {
                loading.value = true;

                try {
                    const familyTreeResponse = await axios.get(`/api/family-trees/${familyTreeId}`);
                    familyTreeData.value = familyTreeResponse.data.data;

                    const personTreeResponse = await axios.get(`/api/persons/${familyTreeData.value.headPerson.id}/tree`);
                    [headPersonTree.value] = personTreeResponse.data.data;
                } catch (error) {
                    serverError.value = `Server error occurred: ${error.response.data.message}`;
                } finally {
                    loading.value = false;
                }
            };

            onMounted(() => fetchFamilyTree(route.params.id));

            return {
                familyTreeData,
                headPersonTree,
                serverError,
                loading,
                store,
            }
        }
    }
</script>

<style lang="scss">
    .family-tree-container {
        max-width: 100%;
        overflow: auto;
    }
</style>
