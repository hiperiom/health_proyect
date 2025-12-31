import { ref } from 'vue';
import axios from 'axios';

export function useRoles() {
    const loading = ref(false);
    const drawerOpen = ref(false);

    const pagination = ref({
        total: 0,
        page: 1,
        pageSize: 8
    });

    const fetchRoles = async (params = {}) => {
        loading.value = true;
        try {
            const page = params.page || pagination.value.page;
            const pageSize = params.results || pagination.value.pageSize;
            const searchText = params.searchText || '';

            const res = await axios.get(route('roles.data'), {
                params: {
                    page: page,      
                    pageSize: pageSize,    
                    searchText: searchText,
                },
            });
            
            // Sincronizamos el estado de la paginación
            pagination.value.page = page;
            pagination.value.pageSize = pageSize;
            
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