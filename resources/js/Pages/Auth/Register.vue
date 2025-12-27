<script>
	export default {
  components: { AvatarUpload },
		name: 'Register',
	};
</script>
<script setup>
	// 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
	import { ref } from 'vue';
	import { LoadingOutlined, UserOutlined } from '@ant-design/icons-vue';
	import { getBase64 } from '@/helpers/helpers';

	import { useRegister } from './Composables/useRegister';
	import { getRegisterRules } from './Utils/registerRules';


	import AuthLayout from '@/Layouts/AuthLayout.vue';
	import Header from '@/Pages/Auth/Components/Header.vue';
	import RegisterTour from './Components/RegisterTour.vue';
	import AvatarCompany from '../../Components/AvatarCompany.vue';
	import RegisterFields from './Components/RegisterFields.vue';
	import AvatarUpload from './Components/AvatarUpload.vue';
	// 2. Props & Emits (defineProps, defineEmits)
	// 3. State (ref, reactive)
	const { 
		registerForm,
		registerFormRef,
		handleLogin,
		handleSubmit,
	} = useRegister();

	const genderOptions = [
			{
			value: 'm',
			label: 'Masculino',
			},
			{
			value: 'f',
			label: 'Femenino',
			},
	];

	const rulesForm = getRegisterRules(registerForm);

	// 4. Computed Properties
	// 5. Methods & Logic (Functions, Handlers)
	// 6. Watchers
	// 7. Lifecycle Hooks (onMounted, etc.)
	// 8. Expose (defineExpose)
</script>

<template>
	<AuthLayout>
		<template #content>
			<a-row justify="center" :wrap="true">
				<a-col :span="24" class="p-2 text-center"></a-col>
				<a-col class="p-4 p-sm-4 p-md-4 p-lg-4 p-xl-4 p-xxl-4" :span="24">
					<a-card class="glass-container fade-in">
						<AvatarCompany />

						<Header title="Nuevo Paciente" />
						<a-spin tip="Espere un momento..." :spinning="registerForm.processing">
							<a-form 
								ref="registerFormRef" 
								layout="vertical" 
								:model="registerForm" 
								:rules="rulesForm"
								@submit.prevent="handleSubmit"
							>
								<a-row justify="center" :gutter="10" :wrap="true">
									<a-col :span="24" class="text-center">
										<AvatarUpload 
											v-model:value="registerForm.avatar" 
                            				:loading="registerForm.processing"
										/>
									</a-col>
								</a-row>
								<RegisterFields :registerForm="registerForm" :genderOptions="genderOptions" />
								
								<a-row justify="center" :gutter="10" :wrap="true">
									<a-col :xs="24" :sm="12" :md="10" :lg="10" :xl="8" class="text-center">
										<a-row justify="center" :wrap="true">
											<a-col :span="24" class="text-center">
												<a-form-item class="mb-2">
													<a-button id="tour-submit" type="primary" html-type="submit"
														:loading="registerForm.processing"
														:disabled="registerForm.processing" block>
														Registrar
													</a-button>
												</a-form-item>
											</a-col>
										</a-row>
									</a-col>
								</a-row>
							</a-form>
						</a-spin>
						<div class="text-center">
							<a-button type="link" @click="handleLogin">
								Ya estoy registrado
							</a-button>
						</div>
					</a-card>
				</a-col>
			</a-row>
			<a-row>
				<a-col :span="24" class="text-center">
					<RegisterTour />
				</a-col>
			</a-row>
		</template>
	</AuthLayout>
</template>
<style scoped></style>
