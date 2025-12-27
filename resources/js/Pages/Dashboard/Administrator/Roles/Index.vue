<script>
    export default {
        name: "RolesPermissionesIndex",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h,ref } from 'vue';
    import { PlusOutlined } from '@ant-design/icons-vue';

    import DashboardLayout from '@/Layouts/DashboardLayout.vue';
    import CreateRole from './Components/Create.vue';
    import ListRole from './Components/List.vue';

    // 2. Props & Emits (defineProps, defineEmits)
    // 3. State (ref, reactive)
    const drawerOpen = ref(false);
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDrawer = (value) => {
        drawerOpen.value = value;
    };
    const handleSubmit = () => {
        alert("Submitted");
    };
    const handleCancelForm = () => {
        alert("Cancelled");
        handleDrawer(false);
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <DashboardLayout >
        <template #header>
            <div class="components-page-header-demo-responsive" style="border: 1px solid rgb(235, 237, 240)">
                <a-page-header 
                    title="Roles" 
                    backIcon="false"
                >
                    <template #extra>
                        <a-button 
                            @click="handleDrawer(true)"
                            :icon="h(PlusOutlined)" type="primary">
                            Nuevo Rol
                        </a-button>
                        
                    </template>

                </a-page-header>
            </div>
        </template>
        <template #content>

            <ListRole />
            <CreateRole 
                :drawerOpen="drawerOpen"
                @handleDrawer="handleDrawer"
                @handleSubmit="handleSubmit" 
                @handleCancelForm="handleCancelForm"
            />
            
        </template>
        
    </DashboardLayout>
</template>


<style lang="scss" scoped>

</style>