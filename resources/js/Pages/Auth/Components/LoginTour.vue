<template>


    <a-float-button
        @click="handleOpen(true)"
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
        v-model:current="currentStep"
        :open="open" 
        type="primary"
        :steps="tourSteps" 
        @close="handleOpen(false)" 
        @finish="handleFinish"
    />
        
   
</template>
<script setup>
    import { ExclamationCircleOutlined, QuestionCircleOutlined } from '@ant-design/icons-vue';
    import { Modal  } from 'ant-design-vue';
    import { h,ref, createVNode, computed, onMounted } from 'vue';
    
    const props = defineProps({
        current: { type: Number, default: 0 },
        step1: { type: Object, default: null },
        step2: { type: Object, default: null },
        step3: { type: Object, default: null }
    });
    const emit = defineEmits(['update:current', 'update:open', 'close']);
    const open = ref(false);
    

    const currentStep = computed({
        get: () => props.current,
        set: (val) => emit('update:current', val)
    });

    const tourSteps = computed(() => [
        {
            cover: createVNode('img', {
                alt: 'salud_chacao.png',
                style: 'width: 50px; height: 50px;',
                src: 'https://www.antdv.com/assets/logo.1ef800a8.svg',
            }),
            title: createVNode('div', { class: 'fs-3 text-center m-0' }, '¡Estimado vecino!'),
            description: `
                Bienvenido al sistema de solicitud de citas de Salud Chacao. 
                Por favor, sigue los pasos para aprender a usar el portal.
            `,
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', 'Siguiente') 
            },

        },
        {
            title: 'Para ingresar a tu cuenta',
            description: `Ingresa tu correo electrónico o cédula junto con tu contraseña para acceder a tu cuenta.`,
            target: () => props.step1?.$el || props.step1,
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', 'Continuar')
            },
        },
        {
            title: 'Si no estas registrado',
            description: 'Pulsa aquí para registrarte como paciente.',
            target: () => props.step2?.$el || props.step2,
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', 'Continuar')
            },

            
        },
        {
            title: 'Recuperar Acceso',
            description: '¿Olvidaste tu contraseña? Solicita una nueva aquí.',
            target: () => props.step3?.$el || props.step3,
            prevButtonProps: { 
                children: () => h('span', 'Volver') 
            },
            nextButtonProps: { 
                children: () => h('span', 'Continuar')
            },
        },
    ]);
    
    const handleFinish = () => {
        Modal.confirm({
            title: "¿No ver más esta guia?",
            icon: createVNode(ExclamationCircleOutlined),
            centered: true,
            content: "Puedes volver a ver esta guía en cualquier momento haciendo clic en el ícono de ayuda.",
            onOk() {
                localStorage.setItem('login_tour', 'true');
            },
            onCancel() {
                localStorage.setItem('login_tour', 'false');
            },
            class: 'test',
            okText: "Sí, no mostrar de nuevo",
            cancelText: "Seguir viendo",
            class: 'modal-primary',
        });
        open.value = false;
    };
    const handleOpen = (val) => {
        currentStep.value = 0;
        localStorage.setItem('login_tour', 'false');
        open.value = val;
    };

    onMounted(() => {
        const hideTour = localStorage.getItem('login_tour') === 'true';

        if (!hideTour) {
            setTimeout(() => {
                open.value = true;
            }, 800);
        }
    });
</script>
<style>
    /* Personaliza el fondo de la burbuja */
    .ant-tour-primary .ant-tour-inner {
        background-color: #004a63 !important; /* El azul de Salud Chacao */
        border-radius: 12px;
    }

    /* Personaliza la flechita que apunta al elemento */
    .ant-tour-primary .ant-tour-arrow-content {
        background-color: #004a63 !important;
    }

    /* Personaliza el botón "Siguiente" dentro del tour */
    .ant-tour-primary .ant-tour-next-btn {
        background-color: #ffffff !important;
        color: #004a63 !important;
        border: none;
    }

    /* Personaliza el botón "Anterior" */
    .ant-tour-primary .ant-tour-prev-btn {
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
    }
    /* Contenedor principal del modal */
    .modal-primary .ant-modal-content {
        background-color: #004a63 !important; /* Azul Salud Chacao */
        color: white !important;
        border-radius: 12px;
    }

    /* Título del modal */
    .modal-primary .ant-modal-confirm-title {
        color: white !important;
        font-size: 1.2rem;
        font-weight: bold;
    }

    /* Texto del contenido */
    .modal-primary .ant-modal-confirm-content {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    /* Botón de Cancelar (Seguir viendo) */
    .modal-primary .ant-btn-default {
        background: transparent !important;
        color: white !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
    }

    .modal-primary .ant-btn-default:hover {
        border-color: white !important;
        background: rgba(255, 255, 255, 0.1) !important;
    }

    /* Botón de Confirmar (Sí, no mostrar de nuevo) */
    .modal-primary .ant-btn-primary {
        background-color: white !important;
        color: #004a63 !important;
        border: none !important;
        font-weight: bold;
    }
</style>