<template lang = "pug">
.info
	.info__title Информация
	.info__data
		.input
			.input__title ФИО
			input.input__box(:disabled = "!editable" v-model="info.name")
		.input
			.input__title Телефон
			the-mask.input__box(:disabled = "!editable" :mask="['+7 (###) ###-##-##']" v-model="info.phone")
		.input
			.input__title e-mail
			input.input__box(:disabled = "!editable" :class = "{ invalid: !emailValid  }" v-model="info.email")
		.input
			.input__title Адрес
			input.input__box(:disabled = "!editable" v-model="info.address")
		.input
			.input__title Номер дисконтной карты
			the-mask.input__box(:disabled = "!editable" :mask="['#### #### #### ####']" v-model="info.discount")
	.info__edit(v-if = "!editable" :disabled = "!loaded" @click = "toggleMode()") редактировать данные
	.info__edit(v-else :disabled = "!loaded || !emailValid" @click = "toggleMode()") сохранить данные

</template>
<script>
import {mapActions, mapGetters} from 'vuex'
import { required, email } from 'vuelidate/lib/validators'

export default {
	data() {
		return {
			editable: false,
			loaded: false,
			info: {
				name: '',
				phone: '',
				email: '',
				discount: '',
				address: ''
			}
		};
	},
	validations: {
		info: {
		 	email : {
				required,
				email
			}
		}
	},
	computed: {
		...mapGetters('user',["getUser"]),
		emailValid(){
			return this.$v.info.email.email && this.$v.info.email.required
		}
	},
	methods: {
		...mapActions('user',["fetchUser", "saveUser"]),
		toggleMode(){
			if (this.editable){
				this.saveChanges()
			}
			this.editable = !this.editable
		},
		async saveChanges(){
			this.saveUser(this.info)
		},
	},
	async created(){
		await this.fetchUser()
		this.info = Object.assign(this.getUser)
		this.loaded = true
	}
}
</script>

<style lang = "scss">
.invalid {
	border: 1px solid red;
	border-radius: 6px;
}
</style>
