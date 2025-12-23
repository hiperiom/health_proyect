<script>
    export default {
        name: "LoginForm",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { computed, ref } from 'vue';

    // 2. Props & Emits (defineProps, defineEmits)
    defineProps({
        loginForm: { type: Object, required: true },
        rulesForm: { type: Object, required: true },
     
    });
    const tourStep1 = ref(null);
    const emit = defineEmits(['handleSubmit']);

   
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleFinishFailed = (errorInfo) => {
        console.error('Errores de validación:', errorInfo);
    };
    const handleSubmit = () => {
        emit('handleSubmit'); 
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
    defineExpose({
        tourStep1
    });
</script>
<template>
    <a-spin  tip="Espere un momento..." :spinning="loginForm.processing">
        <a-form 
            layout="vertical" 
            :model="loginForm" 
            :rules="rulesForm"
            @submit.prevent="handleSubmit"
        >
            <a-row ref="tourStep1" justify="center" :gutter="10" :wrap="true" >
                <a-col :span="24">
                    <a-form-item ref="email" name="email" has-feedback label="Correo electrónico o cédula">
                        <a-input 
                            name="username"
                            v-model:value="loginForm.email" 
                            placeholder="Escribe aquí tu correo electrónico o cédula" 
                        />
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item ref="password" name="password" has-feedback label="Contraseña">
                        <a-input-password 
                            name="password"
                            v-model:value="loginForm.password" 
                            placeholder="Escribe aquí tu contraseña" 
                        />
                    </a-form-item>
                </a-col>
            
                <a-col :span="24">
                    <a-form-item >
                        <a-button type="primary" html-type="submit" :loading="loginForm.processing" :disabled="loginForm.processing" block>
                            Iniciar Sesión
                        </a-button>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row ref="step2"  justify="center" :gutter="10" :wrap="true" >
                <a-col :span="24" class="text-center">
                    <a-form-item v-if="false" name="remember" >
                        <a-checkbox v-model:checked="loginForm.remember">Recordarme</a-checkbox>
                    </a-form-item>
                </a-col>
            </a-row>

        </a-form>
    </a-spin> 
</template>
<style lang="css" scoped>

</style>