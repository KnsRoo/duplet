<template lang = "pug">
.image__slider.swiper-container.similar__things_slider(ref="slider")
	.image__slider_wrapper.swiper-wrapper
		.swiper-slide(v-for = "item in products")
			Product(:product = "item")
	.swiper-pagination



</template>

<script>
import ky from 'ky'
import { getConfig } from '../../../../js/components/sliderConfig'
import Product from './slider-inner.vue'
import Swiper from 'swiper/bundle';
import 'swiper/swiper-bundle.css';

export default {
	data(){
		return {
			products: [],
			similarSlider: undefined
		}
	},
	props: {
		links: Array
	},
	components: {
		Product
	},
	computed: {

	},
	methods: {
		initSlider(){
			this.similarSlider = new Swiper(this.$refs["slider"], getConfig().imgs)
		}
	},
	mounted(){
		this.initSlider()
        // setTimeout(() => {
        //     this.similarSlider.update();
        // }, 1000);
	},
	async created(){
		if (this.$props.links){
			this.$props.links.forEach(async val => {
				let product = await ky.get(val).json()
				this.products.push(product)
			})
		}
	}
}
</script>
