<template lang = "pug">
.card__seen_slider.swiper-container(ref="slider")
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
			seenSlider: undefined
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
			this.seenSlider = new Swiper(this.$refs["slider"], getConfig().cardItemSeen)
		}
	},
	mounted(){
		this.initSlider()
        setTimeout(() => {
            this.seenSlider.update();
        }, 500);
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
