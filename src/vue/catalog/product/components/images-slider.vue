<template lang = "pug">
.slider__box
	.swiper-container.slider__sidebar
		.swiper-wrapper
			.swiper-slide(v-for = "item in images")
				.slider__sidebar_box
					img.slider__sidebar_img(:src="item" alt='')
	.swiper-container.slider__main
		.swiper-wrapper
			.swiper-slide(v-for = "item in images")
				.slider__main_box
					img.slider__main_img(:src="item" alt='')
</template>

<script>
export default {
	data(){
		return {
			images: [],
		}
	},
	props: {
		product: Object
	},
	computed: {

	},
	methods: {

	},
	created(){
		this.images.push(this.$props.product.picture)
	}
}
</script>

<style scoped lang = "scss">
</style>