import { ref } from 'vue';
import axios from 'axios';

export function useRoles() {
    const loading = ref(false);
    const drawerOpen = ref(false);
    const pagination = ref({
        total: 0,
        current: 1,
        pageSize: 7,
    });

    const fetchRoles = async (params = {}) => {
        loading.value = true;
        try {
            const page = params.page || pagination.value.current;
            const limit = params.results || pagination.value.pageSize;
            const searchText = params.searchText || '';

            const res = await axios.get('https://jsonplaceholder.typicode.com/users', {
                params: {
                    _page: page,
                    _limit: limit,
                    q: searchText,
                },
            });

            // Sincronizamos el estado de la paginación
            pagination.value.current = page;
            pagination.value.pageSize = limit;
            
            return res;
        } catch (error) {
            console.error("Error fetching roles:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    const handleDrawer = (value) => {
        drawerOpen.value = value;
    };

    return {
        loading,
        drawerOpen,
        pagination,
        fetchRoles,
        handleDrawer
    };
}