// resources/js/Composables/useRolesList.js
import { ref } from 'vue';

export function useRolesList(fetchRolesApi, pagination) {
    const dataSource = ref([]);
    const searchText = ref('');

    const loadData = async (params = {}) => {
        // Sincronizar parámetros de paginación
        if (params.page) pagination.value.current = params.page;
        if (params.results) pagination.value.pageSize = params.results;
        
        // Preparar búsqueda
        const fetchParams = {
            ...params,
            searchText: params.searchText !== undefined ? params.searchText : searchText.value
        };

        const res = await fetchRolesApi(fetchParams);
        
        if (res && res.data) {
            dataSource.value = res.data;
            // Actualizar total desde headers (específico de JSONPlaceholder o tu API)
            const totalCount = res.headers['x-total-count'] || 10;
            pagination.value.total = parseInt(totalCount);
        }
    };

    const handleTableChange = (pag, filters, sorter) => {
        loadData({
            results: pag.pageSize,
            page: pag.current,
            sortField: sorter.field,
            sortOrder: sorter.order,
            ...filters,
        });
    };

    const handleSearch = () => {
        loadData({ page: 1 }); // Al buscar, siempre volvemos a la pág 1
    };
    const handleRefresh = () => {
        searchText.value='';
        loadData({ page: 1 });
    };
    return {
        dataSource,
        searchText,
        loadData,
        handleRefresh,
        handleTableChange,
        handleSearch
    };
}