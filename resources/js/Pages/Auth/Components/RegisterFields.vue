<script>
    export default {
        name: "RegisterFields",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    // 2. Props & Emits (defineProps, defineEmits)
    defineProps(['registerForm', 'genderOptions']);
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
            <a-form-item name="dni" ref="dni" has-feedback label="Cédula">
                <a-input name="dni" :maxlength="8" v-model:value="registerForm.dni"
                    placeholder="Escribe aquí tu cédula" />
            </a-form-item>
        </a-col>
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
            <a-form-item name="email" ref="email" has-feedback label="Correo electrónico">
                <a-input name="email" v-model:value="registerForm.email"
                    placeholder="Escribe aquí tu correo electrónico" />
            </a-form-item>
        </a-col>
    </a-row>
    <a-row id="tour-names" justify="center" :gutter="10" :wrap="true">
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
            <a-form-item name="first_names" ref="first_names" has-feedback label="Nombres">
                <a-input name="first_names" :maxlength="50"
                    v-model:value="registerForm.first_names"
                    placeholder="Escribe tu primer y segundo nombre" />
            </a-form-item>
        </a-col>
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
            <a-form-item name="last_names" ref="last_names" has-feedback label="Apellidos">
                <a-input name="last_names" :maxlength="50"
                    v-model:value="registerForm.last_names"
                    placeholder="Escribe tu primer y segundo apellido" />
            </a-form-item>
        </a-col>
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
            <a-form-item name="gender" has-feedback label="Género">
                <a-select name="gender" placeholder="Selecciona el sexo"
                    v-model:value="registerForm.gender" :options="genderOptions"
                    style="width: 100%" />
            </a-form-item>
        </a-col>
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
            <a-form-item name="birthday" ref="birthday" has-feedback
                label="Fecha de nacimiento">
                <a-date-picker v-model:value="registerForm.birthday" style="width: 100%"
                    format="DD/MM/YYYY"
                    placeholder="Escribe tu fecha de nacimiento dia/mes/año" />
            </a-form-item>
        </a-col>
    </a-row>
    <a-row id="tour-password" justify="center" :gutter="10" :wrap="true">
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
            <a-form-item name="password" ref="password" hasFeedback label="Contraseña">
                <a-input-password name="password" v-model:value="registerForm.password"
                    placeholder="Escribe aquí tu contraseña" />
            </a-form-item>
        </a-col>
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
            <a-form-item name="password_confirmation" ref="password_confirmation"
                hasFeedback label="Confirmar Contraseña">
                <a-input-password name="password_confirmation"
                    v-model:value="registerForm.password_confirmation"
                    placeholder="Confirma aquí tu contraseña" />
            </a-form-item>
        </a-col>
    </a-row>
    <a-row justify="center" :gutter="10" :wrap="true">
        <a-col :span="24" class="text-center">
            <a-form-item v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                <a-checkbox v-model:checked="registerForm.terms">
                    Acepto los términos y condiciones
                </a-checkbox>
            </a-form-item>
        </a-col>
    </a-row>
</template>
<style lang="css" scoped>

</style>