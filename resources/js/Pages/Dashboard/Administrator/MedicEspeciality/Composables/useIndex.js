import { ref } from 'vue';
import axios from 'axios';

export function useIndex(config) {
    const loading = ref(false);
    const dataSource = ref([]);
    const searchText = ref('');
    const pagination = ref({
        total: 0,
        page: 1,
        pageSize: config.defaultPageSize || 8
    });

    const handleTableChange = (pag, filters, sorter) => {
        loadData({
            results: pag.pageSize || pagination.value.pageSize,
            page: pag.current,
            sortField: sorter.field,
            sortOrder: sorter.order,
            ...filters,
        });
    };

    const handleSearch = () => {
        loadData({ page: 1 }); 
    };

    const handleRefresh = () => {
        searchText.value='';
        loadData({ current: 1 });
    };

    const loadData = async (params = {}) => {
        if (params.current) pagination.value.current = params.current;
        if (params.pageSize) pagination.value.pageSize = params.pageSize;
        
        const fetchParams = {
            ...params,
            searchText: params.searchText !== undefined 
                ? params.searchText 
                : searchText.value
        };

        const res = await fetchRecords(fetchParams);
        if (res && res.data) {
            dataSource.value = res.data.data || res.data;
            const totalCount =  res.data.total 
                                || (Array.isArray(res.data) ? res.data.length : 0);
            pagination.value.total = parseInt(totalCount);
        }
    };
    
    const fetchRecords = async (params = {}) => {
        loading.value = true;
        try {
            const page = params.page || pagination.value.page;
            const pageSize = params.results || pagination.value.pageSize;
            const searchText = params.searchText || '';

            const res = await axios.get(route(config.modelNameRoutes +'.data'), {
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
            console.error("Error obteniendo registros:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        pagination,
        dataSource,
        searchText,
        loadData,
        handleRefresh,
        handleTableChange,
        handleSearch
    };
}
