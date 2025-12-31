// resources/js/Composables/useRolesList.js
import { ref } from 'vue';

export function useRolesList(fetchRolesApi, pagination) {
    const dataSource = ref([]);
    const searchText = ref('');

    const loadData = async (params = {}) => {
        // Sincronizar parámetros de paginación
        if (params.current) pagination.value.current = params.current;
        if (params.pageSize) pagination.value.pageSize = params.pageSize;
        
        // Preparar búsqueda
        const fetchParams = {
            ...params,
            searchText: params.searchText !== undefined 
                ? params.searchText 
                : searchText.value
        };

        const res = await fetchRolesApi(fetchParams);
        console.log(res.data);
        if (res && res.data) {
            dataSource.value = res.data.data || res.data;
            // Actualizar total desde headers (específico de JSONPlaceholder o tu API)
            const totalCount =  res.data.total 
                                || (Array.isArray(res.data) ? res.data.length : 0);
            pagination.value.total = parseInt(totalCount);
        }
    };

    const handleTableChange = (pag, filters, sorter) => {
        console.log("handleTableChange",pag);
        loadData({
            results: pag.pageSize || pagination.value.pageSize,
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
        loadData({ current: 1 });
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