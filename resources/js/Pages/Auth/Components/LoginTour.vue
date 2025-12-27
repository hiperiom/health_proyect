
<script>
    export default {
        name: "LoginTour",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h,ref,computed, onMounted } from 'vue';
    import {  QuestionCircleOutlined } from '@ant-design/icons-vue';

    // 2. Props & Emits (defineProps, defineEmits)
    // 3. State (ref, reactive)
    const openTour = ref(false);
    // 4. Computed Properties

    const tourSteps = computed(() => [
        {
            cover: h(
                'img', 
                { 
                    alt: 'salud_chacao.png',
                    style: 'width: 3rem; height: 3rem;',
                    src: 'https://www.antdv.com/assets/logo.1ef800a8.svg',
                }
            ),
            title: h(
                'div', 
                { class: 'fs-4 d-flex flex-column align-items-center m-0 p-0' }, 
                '¡Tu salud a un clic de distancia!'
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Bienvenido al nuevo portal de Salud. Agenda, consulta y gestiona tus citas médicas en segundos.
                Permítenos mostrarte cómo aprovechar todas las herramientas que tenemos para ti.
            `),
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', '¡Muestrame!') 
            },
            mask: true,
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                'Entra a tu espacio de salud'
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Solo necesitas tu cédula o correo además de tu contraseña para gestionar tus citas desde cualquier lugar.
            `),
            target: () => document.getElementById('tour-auth-form'),
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', 'Continuar')
            },
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                '¿Eres nuevo por aquí?'
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Crea tu perfil de paciente ahora mismo. Es el primer paso para tomar el control de tu bienestar y el de tu familia.
            `),
            target: () => document.getElementById('tour-register-button'),
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', 'Continuar')
            },     
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                '¿Olvidaste tu clave? No te preocupes.'
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Recuperar el acceso es muy fácil. Haz clic aquí y te guiaremos paso a paso para que vuelvas a ingresar de forma segura y sin complicaciones.
            `),
            target: () => document.getElementById('tour-forgot-password-button'),
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', 'Continuar')
            },
        },
        {
            title: h(
                'div', 
                { class: 'fs-4 text-center m-0 p-0' }, 
                '¡Todo listo! Estás a un paso de cuidarte.'
            ),
            description: h('div', { class: 'd-flex flex-column text-center fs-6' }, 
                h(
                    'div', 
                    { }, 
                    `
                        Ahora que conoces las funciones principales, estás listo para explorar y aprovechar al máximo tu portal de salud.\n 
                    `
                ),
                h(
                    'div', 
                    { }, 
                    `
                        ¡Tu bienestar está en tus manos!
                    `
                ),
                h(
                    'div', 
                    { }, 
                    'Puedes volver a ver esta guía en cualquier momento haciendo clic en el ícono de ayuda.'
                )
            ),
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', 'Finalizar')
            },
            mask: true,
        },
    ]);
    
    // 5. Methods & Logic (Functions, Handlers)
    const handleFinishTour = () => {
        localStorage.setItem('login_tour', 'true');
    };
    const handleOpenTour = (val) => {
        openTour.value = val;
    };

    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    onMounted(() => {
        const hideTour = localStorage.getItem('login_tour') === 'true';

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