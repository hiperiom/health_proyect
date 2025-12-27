
<script>
    export default {
        name: "RegisterTour",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h,ref,computed, onMounted } from 'vue';
    import {  CameraOutlined, IdcardOutlined, LockOutlined, QuestionCircleOutlined, UserOutlined } from '@ant-design/icons-vue';

    // 2. Props & Emits (defineProps, defineEmits)
    // 3. State (ref, reactive)
    const openTour = ref(false);
    const tourLocale = {
        next: 'Siguiente',
        previous: 'Anterior',
        finish: 'Finalizar',
        };
    // 4. Computed Properties

    const tourSteps = computed(() => [

        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                '¡Bienvenido a tu espacio de salud!'
            ),
            description: h('div', 
            { class: 'fs-6 text-center m-0 p-0' }, 
            [
                h('p', 'Estamos aquí para facilitarte el acceso a la salud.'),
                h('p', { class: 'mt-2' }, 'En este breve recorrido te enseñaremos cómo completar tu registro correctamente para empezar a disfrutar de nuestros servicios.')
            ]),
            prevButtonProps: { 
                children: () => 'Volver'
            },
            nextButtonProps: { 
                children: () => 'Continuar'
            },
            mask: true, 
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                [h(CameraOutlined), ' Tu foto de perfil']
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Comienza subiendo una foto clara. Esto nos ayuda a identificarte rápidamente en el centro de salud.
            `),
            target: () => document.getElementById('tour-avatar'),
            prevButtonProps: { 
                children: () => 'Volver'
            },
            nextButtonProps: { 
                children: () => 'Continuar'
            },
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                [h(IdcardOutlined), ' Datos personales']
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Ingresa tu cédula y correo. Asegúrate de que el correo esté activo para recibir tus confirmaciones de cita.
            `),
            target: () => document.getElementById('tour-identity'),
            prevButtonProps: { 
                children: () => 'Volver'
            },
            nextButtonProps: { 
                children: () => 'Continuar'
            },
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                [h(UserOutlined), ' Identificación']
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Escribe tus nombres, apellidos, selecciona tu género y fecha de nacimiento. Estos datos deben coincidir con tu documento de identidad.
            `),
            target: () => document.getElementById('tour-names'),
            prevButtonProps: { 
                children: () => 'Volver'
            },
            nextButtonProps: { 
                children: () => 'Continuar'
            },
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                [h(LockOutlined), ' Seguridad']
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Crea una contraseña segura que no compartas con nadie. Deberás confirmarla para evitar errores.
            `),
            target: () => document.getElementById('tour-password'),
            prevButtonProps: { 
                children: () => 'Volver'
            },
            nextButtonProps: { 
                children: () => 'Continuar'
            },
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                '¡Todo listo!'
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Haz clic en "Registrar" para crear tu cuenta y empezar a agendar tus citas en el sistema.
            `),
            target: () => document.getElementById('tour-submit'),
            prevButtonProps: { 
                children: () => 'Volver'
            },
            nextButtonProps: { 
                children: () => 'Finalizar'
            },
        },
    ]);
    
    // 5. Methods & Logic (Functions, Handlers)
    const handleFinishTour = () => {
        localStorage.setItem('register_tour', 'true');
    };
    const handleOpenTour = (val) => {
        openTour.value = val;
    };

    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    onMounted(() => {
        const hideTour = localStorage.getItem('register_tour') === 'true';

        if (!hideTour) {
            setTimeout(() => {
                openTour.value = true;
            }, 800);
        }
    });
    // 8. Expose (defineExpose)
</script>
<template>


    <a-float-button
        @click="handleOpenTour(true)"
        type="default"
        :style="{
            right: '24px',
        }"
    >
        <template #icon>
            <QuestionCircleOutlined />
        </template>
    </a-float-button>

    <a-tour 
        :open="openTour" 
        type="primary"
        :steps="tourSteps" 
        @close="handleOpenTour(false)" 
        @finish="handleFinishTour"
    />
   
</template>
<style lang="css">

 
</style>