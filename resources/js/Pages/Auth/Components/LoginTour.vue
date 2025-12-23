
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
    const props = defineProps({
        tourCurrentStep: { type: Number, default: 0 },
        tourStep1: { type: Object, default: null },
        tourStep2: { type: Object, default: null },
        tourStep3: { type: Object, default: null }
    });
    const emit = defineEmits(['update:tourCurrentStep'/* ,'update:current', 'update:open', 'close' */]);
    // 3. State (ref, reactive)
    const openTour = ref(false);
    // 4. Computed Properties
    const tourCurrentStep = computed({
        get: () => props.tourCurrentStep,
        set: (val) => emit('update:tourCurrentStep', val)
    });
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
                { class: 'fs-3 d-flex flex-column align-items-center m-0 p-0' }, 
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

        },
        {
            title: h(
                'div', 
                { class: 'fs-3 text-center m-0 p-0' }, 
                'Entra a tu espacio de salud'
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Solo necesitas tu cédula o correo y contraseña para gestionar tus citas desde cualquier lugar.
            `),
            target: () => props.tourStep1?.$el || props.tourStep1,
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
                { class: 'fs-3 text-center m-0 p-0' }, 
                '¿Eres nuevo por aquí?'
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Crea tu perfil de paciente ahora mismo. Es el primer paso para tomar el control de tu bienestar y el de tu familia.
            `),
            target: () => props.tourStep2?.$el || props.tourStep2,
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
                { class: 'fs-3 text-center m-0 p-0' }, 
                '¿Olvidaste tu clave? No te preocupes.'
            ),
            description: h('div', { class: 'text-center fs-6' }, `
                Recuperar el acceso es muy fácil. Haz clic aquí y te guiaremos paso a paso para que vuelvas a ingresar de forma segura y sin complicaciones.
            `),
            target: () => props.tourStep3?.$el || props.tourStep3,
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
                { class: 'fs-3 text-center m-0 p-0' }, 
                '¡Todo listo! Estás a un paso de cuidarte.'
            ),
            description: h('div', { class: 'd-flex flex-column text-center fs-6' }, 
                h(
                    'div', 
                    { }, 
                    `
                        Ahora que conoces las funciones principales, estás listo para explorar y aprovechar al máximo tu portal de salud.\n 
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
                open.value = true;
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
        v-model:tourCurrentStep="tourCurrentStep"
        :open="openTour" 
        type="primary"
        :steps="tourSteps" 
        @close="handleOpenTour(false)" 
        @finish="handleFinishTour"
    />
        
   
</template>
<style lang="css">

 
</style>