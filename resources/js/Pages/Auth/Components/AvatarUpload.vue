<script>
    export default {
        name: "AvatarUpload",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { onMounted, ref, watch } from 'vue';
    import { LoadingOutlined, UserOutlined } from '@ant-design/icons-vue';
    import { getBase64 } from '@/helpers/helpers';
    // 2. Props & Emits (defineProps, defineEmits)
    const props = defineProps({
        value: { type: [Object, File, String], default: null },
        loading: { type: Boolean, default: false }
    });
    const emit = defineEmits(['update:value']);
    // 3. State (ref, reactive)
    const previewVisible = ref(false);
    const previewImage = ref('');
    const previewTitle = ref('');
    const internalFileList = ref([]);
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const initImage = () => {
    if (typeof props.value === 'string' && props.value.startsWith('http')) {
            internalFileList.value = [{
                uid: '-1',
                name: 'Foto actual',
                status: 'done',
                url: props.value, // Aquí es donde ocurre la magia del preview
            }];
        }
    };
    const handleBeforeUpload = (file) => {
        emit('update:value', file); // Notifica al formulario que hay un archivo

        internalFileList.value = [{
            uid: '-1',
            name: file.name,
            status: 'done',
            originFileObj: file,
        }];

        return false; // Detiene la subida automática
    };

    const handlePreview = async (file) => {
        if (!file.url && !file.preview) {
            file.preview = await getBase64(file.originFileObj);
        }
        previewImage.value = file.url || file.preview;
        previewVisible.value = true;
        previewTitle.value = file.name || 'Previsualización';
    };
    const handleCancel = () => {
		previewVisible.value = false;
		previewTitle.value = '';
	};
    const handleRemove = () => {
        // 1. Limpiamos el valor en el padre
        emit('update:value', null);
        
        // 2. Limpiamos la lista interna para que reaparezca el icono de "Subir"
        internalFileList.value = [];
    };
    // 6. Watchers
    watch(() => props.value, (newVal) => {
        if (!newVal) {
            internalFileList.value = [];
        } else if (typeof newVal === 'string' && internalFileList.value.length === 0) {
            initImage();
        }
    }, { immediate: true });
    // 7. Lifecycle Hooks (onMounted, etc.)
    onMounted(() => {
        initImage();
    });
    // 8. Expose (defineExpose)
</script>
<template>
    <div id="tour-avatar" class="text-center mb-6">
        <a-upload 
            id="tour-avatar" 
            v-model:file-list="internalFileList"
            list-type="picture-card" 
            :max-count="1"
            :show-upload-list="true"
            :before-upload="handleBeforeUpload" 
            @preview="handlePreview"
            @remove="handleRemove"
        >
            <div v-if="!value">
                <LoadingOutlined v-if="loading" />
                <UserOutlined v-else />
                <div style="margin-top: 8px">Subir Foto</div>
            </div>
        </a-upload>
    </div>
    <a-modal 
        :open="previewVisible" 
        :title="previewTitle" 
        :footer="null"
        @cancel="handleCancel"
    >
        <img alt="example" style="width: 100%" :src="previewImage" />
    </a-modal>
</template>
<style lang="css" scoped>

</style>