<script setup>
import { h } from 'vue';
import { PlusOutlined, QuestionCircleOutlined } from '@ant-design/icons-vue';

// Layouts y Componentes
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import CreateRole from './Components/Create.vue';
import ListRole from './Components/List.vue';
import Spinner from '@/Components/Spinner.vue';
// Composable (Lógica de negocio)
import { useRoles } from './Composables/useRoles';

const { 
    loading, 
    drawerOpen, 
    pagination, 
    fetchRoles, 
    handleDrawer 
} = useRoles();

// Handlers específicos de UI
const handleSubmit = () => alert("Submitted");
const handleHelp = () => alert("Help");

const handleCancelForm = () => {
    alert("Cancelled");
    handleDrawer(false);
};
</script>

<template>
    <Spinner :loading="false">
        <DashboardLayout>
            <template #header>
                
                    <a-page-header title="Roles" backIcon="false">
                        <template #extra>
                            <a-button 
                                type="primary" 
                                :icon="h(PlusOutlined)" 
                                @click="handleDrawer(true)"
                            >
                                Nuevo Rol
                            </a-button>
                            <a-button 
                                :icon="h(QuestionCircleOutlined)" 
                                @click="handleHelp" 
                            />
                        </template>
                    </a-page-header>
            
            </template>

            <template #content>
                <ListRole 
                    :loading="loading"
                    :pagination="pagination"
                    :fetchRoles="fetchRoles"
                /> 

                <CreateRole 
                    :drawerOpen="drawerOpen"
                    @handleDrawer="handleDrawer"
                    @handleSubmit="handleSubmit" 
                    @handleCancelForm="handleCancelForm"
                />
            </template>
        </DashboardLayout>
    </Spinner>
</template>