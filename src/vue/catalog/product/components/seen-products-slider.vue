<template lang = "pug">
.image__slider.swiper-container.recently__seen_slider(ref = "slider")
	.image__slider_wrapper.swiper-wrapper
		.swiper-slide(v-for="item in products")
			Product(:product = "item")
	.swiper-pagination
</template>

<script>
import ky from 'ky'
import { getConfig } from '../../../../js/components/sliderConfig'
import Seen from '../../../../js/components/seenStore'
import Product from './slider-inner.vue'
import Swiper from 'swiper/bundle';
import 'swiper/swiper-bundle.css';

export default {
	data(){
		return {
			products: [],
			productsSlider: undefined
		}
	},
	components: {
		Product,
	},
	computed: {

	},
	methods: {
		initSlider(){
			this.productsSlider = new Swiper(this.$refs['slider'], getConfig().imgs);
		}
	},
	mounted(){
		this.initSlider()
        setTimeout(() => {
            this.productsSlider.update();
        }, 500);
	},
	async created(){
		let products = new Seen('seenList').get()
		console.log(products)
		if (products){
			products.forEach(async val => {
				let product = await ky.get(val).json()
				this.products.push(product)
			})
		}
	}
}
</script>
