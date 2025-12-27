<script>
    export default {
        name: "LoginFields",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    // 2. Props & Emits (defineProps, defineEmits)
    defineProps(['loginForm']);
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-row id="tour-auth-form" justify="center" :gutter="10" :wrap="true" >
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
    <a-row  justify="center" :gutter="10" :wrap="true" >
        <a-col :span="24" class="text-center">
            <a-form-item v-if="false" name="remember" >
                <a-checkbox v-model:checked="loginForm.remember">Recordarme</a-checkbox>
            </a-form-item>
        </a-col>
    </a-row>
</template>
<style lang="css" scoped>

</style>