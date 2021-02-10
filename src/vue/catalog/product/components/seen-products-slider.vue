<template lang = "pug">
.image__slider.swiper-container.recently__seen_slider
	.image__slider_wrapper.swiper-wrapper
		.swiper-slide(v-for="item in products")
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
