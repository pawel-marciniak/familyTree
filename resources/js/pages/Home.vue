<template>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-primary" @click="showModal = true">
                    Add new family tree
                </button>

                <div v-if="familyTreesError" class="alert alert-danger" role="alert">
                    {{ familyTreesError }}
                </div>

                <table class="table table-hover table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="familyTree in familyTrees"
                                     :key="familyTree.id"
                                     @click="router.push({ name: 'family-tree', params: { id: familyTree.id } })"
                        >
                            <td>{{ familyTree.name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <family-tree-modal :opened="showModal"
                           @close="showModal = false"
                           @saved="saveFamily"
        />
    </div>
</template>

<script>
    import { ref, onMounted } from 'vue';
    import FamilyTreeModal from '../components/FamilyTreeModal';
    import { useRouter } from 'vue-router';

    export default {
        name: 'Home',

        components: { FamilyTreeModal },

        setup() {
            const familyTrees = ref([]);
            const familyTreesError = ref(null);
            const showModal = ref(false);
            const router = useRouter();

            const fetchFamilyTrees = async () => {
                try {
                    const { data } = await axios.get('/api/family-trees');

                    return data.data;
                } catch (error) {
                    familyTreesError.value = `Error occurred while trying to fetch family trees: ${error.response.data.message}`;

                    return [];
                }
            };

            const saveFamily = (familyData) => {
                familyTrees.value.push(familyData);

                showModal.value = false;
            };

            onMounted(async () => {
                familyTrees.value = await fetchFamilyTrees();
            })

            return {
                familyTrees,
                familyTreesError,
                showModal,
                saveFamily,
                router,
            }
        }
    }
</script>
