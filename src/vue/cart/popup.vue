<template lang = "pug">
.registration
	.registration__wrapper
		a#close_btn_avtorisation.icon-menu-cancel(@click = "$emit('close')")
		.registration__inner(v-if = "type == 'success'")
			img(src = "/assets/img/icons/order_success.svg")
			.registration__title Ваш заказ успешно оформлен!
			.registration__tip
				span.noaccount Спасибо, что выбираете нас!
			.registration__tip
				span.noaccount Информацию о заказае можно посмотреть в
				a.noaccount__tip(href="/lk") личном кабинете
		.registration__inner(v-if = "type == 'error'")
			img(src = "/assets/img/icons/order_error.svg")
			.registration__title Кажется, возникли проблемы
			.registration__tip
				span.noaccount Мы пытаемся их устранить, повторите Ваш заказ позже или
			.registration__tip
				span.noaccount обратитесь в
				a.noaccount__tip(href="#") службу поддержки 
				span.noaccount . Мы постараемся вам помочь
</template>

<script>
export default{
	data(){
		return {}
	},
	props: {
		type: String
	}
}
</script>