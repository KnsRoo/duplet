<template lang = "pug">
.card__seen_slider.swiper-container
	.image__slider_wrapper.swiper-wrapper
		.swiper-slide(v-for = "item in products")
			Product(:product = "item")
	.swiper-pagination
</template>

<script>
import Product from './slider-inner.vue'

export default {
	data(){
		return {
			products: []
		}
	},
	components: {
		Product
	},
	computed: {

	},
	methods: {

	},
	created(){

	}
}
</script>
