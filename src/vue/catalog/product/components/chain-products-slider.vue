<template lang = "pug">
swiper(ref="slider" :options = "options")
	swiper-slide(v-for = "(item, key) in products" :key = "key")
		Product(:product = "item")
	.swiper-pagination(slot = "pagination")
</template>

<script>
import ky from "ky";
import { getConfig } from "../../../../js/components/sliderConfig";
import Product from "../../components/catalog-item.vue";
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper'
import 'swiper/swiper-bundle.css'

export default {
	data() {
		return {
			products: [],
		};
	},
	props: {
		links: Array
	},
	components: {
		Product,
		Swiper,
		SwiperSlide
	},
	directives: {
		swiper: directive
	},
	computed: {
	  swiper() {
		return this.$refs.slider.$swiper
	  },
	  options(){
	  	return getConfig().cardItemSeen
	  }
	},
	async created() {
		if (this.$props.links) {
			this.$props.links.forEach(async val => {
				let product = await ky.get(val).json();
				this.products.push(product);
			});
		}
	}
};
</script>
