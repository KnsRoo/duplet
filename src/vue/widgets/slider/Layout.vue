<template lang = "pug">
section.popular
	.wrapper
		loader(v-if ="!loaded")
		.popular__block(v-else)
			.popular__block_title
				h2.title_main {{ $parent.$options.settings.title }}
				p.title_description {{ $parent.$options.settings.description }}
			.popular__block_cards
				.choose
					.item
						input#for__all_popular.for__all.custom-radio(type='radio' v-model = "current" value = "all")
						label.for__all_label(for='for__all_popular') Все товары
					.item(v-for = "(item, index) in sliderCategories")
						input.for__hunting.custom-radio(:id = "'inp_'+$parent.$options.settings.id+index" type='radio' v-model = "current" :value = "item")
						label.for__hunting_label(:for="'inp_'+$parent.$options.settings.id+index") {{ item }}
				.choose__mobile Все товары
				swiper(ref="slider" :options = "options")
					swiper-slide(v-for = "(item, key) in sliderProducts" :key = "key")
						Product(:product = "item")
					.swiper-pagination(slot = "pagination")
				//-.image__slider.swiper-container
					.image__slider_wrapper.swiper-wrapper
						.swiper-slide(v-for="item in sliderProducts")
							Product(:product = "item")
					.swiper-pagination
			a.link__to(href='/Catalog') Больше товаров
</template>

<script>
import Product from '../../catalog/components/catalog-item.vue'
import { getConfig } from "../../../js/components/sliderConfig";
import loader from "../../loaders/ellipse.vue"
import { Swiper, SwiperSlide, directive } from "vue-awesome-swiper";
import "swiper/swiper-bundle.css";
import { mapActions, mapGetters } from 'vuex'

export default {
	data(){
		return {
			current: "all",
			loaded: false
		}
	},
	components: {
		Product,
		loader,
        Swiper,
        SwiperSlide
	},
    directives: {
        swiper: directive
    },
	computed: {
		...mapGetters('slider', ['popularCats', 'noveltyCats', 'popular', 'novelty']),
        swiper() {
            return this.$refs.slider.$swiper;
        },
        options() {
            return getConfig().imgs;
        },
		sliderProducts(){
			return (this.$parent.$options.settings.id == 0) 
					? this.novelty
					: this.popular
		},
		sliderCategories(){
			return (this.$parent.$options.settings.id == 0) 
					? this.noveltyCats
					: this.popularCats		
		}
	},
	watch:{
		current(){
			console.log(this.current)
			this.setCategory({name: this.current, type: this.$parent.$options.settings.id})
		}
	},
	methods: {
		...mapActions('slider', ['fetchItems', 'setCategory'])
	},
	async created(){
		await this.fetchItems({link: this.$parent.$options.settings.link, type: this.$parent.$options.settings.id})
		this.loaded = true
	}
}

</script>

<style scoped lang = "scss">
</style>
