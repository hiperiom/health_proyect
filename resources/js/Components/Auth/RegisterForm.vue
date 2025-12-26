<script>
    export default {
        name: "RegisterForm",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { computed, ref, watch } from 'vue';
    import { capitalizeWords, getBase64, normalizeText } from '@/helpers/helpers.js';
    import { LoadingOutlined, UserOutlined } from '@ant-design/icons-vue';
    // 2. Props & Emits (defineProps, defineEmits)
    const props = defineProps({
        registerForm: { type: Object, required: true },
        rulesForm: { type: Object, required: true },
        fileList: { type: Array, required: true },
        loadingAvatar: { type: Boolean, required: true },
        previewVisible: { type: Boolean, required: true }
        /* gender: { type: Array, required: true },
        ,
        , */
        
    });
    const emit = defineEmits([
        'handleSubmit',
        'handleBeforeUpload',
        'handlePreview',
        'handleCancel',
        'handleRemove',
        'update:fileList',
    ]);
    // 3. State (ref, reactive)
    const formRef = ref(null);
    //const previewVisible = ref(false);
    const previewTitle = ref('');
    const previewImage = ref('');

    // 4. Computed Properties
    const fileList = computed({
        get: () => props.fileList,
        set: (val) => emit('update:fileList', val)
    });

    const loadingAvatar = computed({
        get: () => props.loadingAvatar,
        set: (val) => emit('loadingAvatar', val)
    });
    // 5. Methods & Logic (Functions, Handlers)
    const handleSubmit = () => {
        emit('handleSubmit');
    }
    const handlePreview = (file) => emit('handlePreview', file);
    const handleBeforeUpload = (file) => emit('handleBeforeUpload', file);
    const handleCancel = () => { emit('handleCancel'); };
    const handleRemove = () => emit('handleRemove');

    // 6. Watchers

    
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
    defineExpose({
        //previewVisible,
        //previewTitle,
        //previewImage,
        validate: () => formRef.value.validate(),
    });
</script>
<template>
    <a-spin
        tip="Espere un momento..."
        :spinning="registerForm.processing"
    >
        <a-form
        ref="formRef"
        layout="vertical"
        :model="registerForm"
        :rules="rulesForm"
        @submit.prevent="handleSubmit"
        >
           <a-row
                justify="center"
                :gutter="10"
                :wrap="true"
            >
                <a-col
                :span="24"
                class="text-center"
                >
                <div
                    id="tour-avatar"
                    class="text-center mb-6"
                >
                    <a-upload
                        id="tour-avatar"
                        v-model:file-list="fileList"
                        list-type="picture-card"
                        :max-count="1"
                        :before-upload="handleBeforeUpload"
                        @preview="handlePreview"
                        @remove="handleRemove" 
                        :custom-request="({ onSuccess }) => onSuccess('ok')"
                    >
                    <div v-if="fileList.length < 1">
                        <LoadingOutlined v-if="loadingAvatar" />
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
                    <img
                    alt="example"
                    style="width: 100%"
                    :src="previewImage"
                    />
                </a-modal>
                aaa {{ previewImage }}
                </a-col>
            </a-row>
             <!-- 
            <a-row
                id="tour-identity"
                justify="center"
                :gutter="10"
                :wrap="true"
            >
                <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                :xl="12"
                :xxl="12"
                >
                <a-form-item
                    name="dni"
                    ref="dni"
                    has-feedback
                    label="Cédula"
                >
                    <a-input
                    name="dni"
                    :maxlength="8"
                    v-model:value="registerForm.dni"
                    placeholder="Escribe aquí tu cédula"
                    />
                </a-form-item>
                </a-col>
                <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                :xl="12"
                :xxl="12"
                >
                <a-form-item
                    name="email"
                    ref="email"
                    has-feedback
                    label="Correo electrónico"
                >
                    <a-input
                    name="email"
                    v-model:value="registerForm.email"
                    placeholder="Escribe aquí tu correo electrónico"
                    />
                </a-form-item>
                </a-col>
            </a-row>
            <a-row
                id="tour-names"
                justify="center"
                :gutter="10"
                :wrap="true"
            >
                <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                :xl="12"
                :xxl="12"
                >
                <a-form-item
                    name="first_names"
                    ref="first_names"
                    has-feedback
                    label="Nombres"
                >
                    <a-input
                    name="first_names"
                    :maxlength="50"
                    v-model:value="registerForm.first_names"
                    placeholder="Escribe tu primer y segundo nombre"
                    />
                </a-form-item>
                </a-col>
                <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                :xl="12"
                :xxl="12"
                >
                <a-form-item
                    name="last_names"
                    ref="last_names"
                    has-feedback
                    label="Apellidos"
                >
                    <a-input
                    name="last_names"
                    :maxlength="50"
                    v-model:value="registerForm.last_names"
                    placeholder="Escribe tu primer y segundo apellido"
                    />
                </a-form-item>
                </a-col>
                <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                :xl="12"
                :xxl="12"
                >
                <a-form-item
                    name="gender"
                    has-feedback
                    label="Género"
                >
                    <a-select
                    name="gender"
                    placeholder="Selecciona el sexo"
                    v-model:value="registerForm.gender"
                    :options="gender"
                    style="width: 100%"
                    />
                </a-form-item>
                </a-col>
                <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                :xl="12"
                :xxl="12"
                >
                <a-form-item
                    name="birthday"
                    ref="birthday"
                    has-feedback
                    label="Fecha de nacimiento"
                >
                    <a-date-picker
                    v-model:value="registerForm.birthday"
                    style="width: 100%"
                    format="DD/MM/YYYY"
                    placeholder="Escribe tu fecha de nacimiento dia/mes/año"
                    />
                </a-form-item>
                </a-col>
            </a-row>
            <a-row
                id="tour-password"
                justify="center"
                :gutter="10"
                :wrap="true"
            >
                <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                :xl="12"
                :xxl="12"
                >
                <a-form-item
                    name="password"
                    ref="password"
                    hasFeedback
                    label="Contraseña"
                >
                    <a-input-password
                    name="password"
                    v-model:value="registerForm.password"
                    placeholder="Escribe aquí tu contraseña"
                    />
                </a-form-item>
                </a-col>
                <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                :xl="12"
                :xxl="12"
                >
                <a-form-item
                    name="password_confirmation"
                    ref="password_confirmation"
                    hasFeedback
                    label="Confirmar Contraseña"
                >
                    <a-input-password
                    name="password_confirmation"
                    v-model:value="registerForm.password_confirmation"
                    placeholder="Confirma aquí tu contraseña"
                    />
                </a-form-item>
                </a-col>
            </a-row>
            <a-row
                justify="center"
                :gutter="10"
                :wrap="true"
            >
                <a-col
                :span="24"
                class="text-center"
                >
                <a-form-item v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                    <a-checkbox v-model:checked="registerForm.terms">
                    Acepto los términos y condiciones
                    </a-checkbox>
                </a-form-item>
                </a-col>
            </a-row>
        -->
            <a-row
                justify="center"
                :wrap="true"
            >
                <a-col
                :span="24"
                class="text-center"
                >
                <a-form-item class="mb-2">
                    <a-button
                    id="tour-submit"
                    type="primary"
                    html-type="submit"
                    :loading="registerForm.processing"
                    :disabled="registerForm.processing"
                    block
                    >
                    Registrar
                    </a-button>
                </a-form-item>
                </a-col>
            </a-row>
        </a-form>
    </a-spin>
</template>
<style lang="css" scoped>

</style>