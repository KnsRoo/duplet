<template lang = "pug">
section.popular
	.wrapper
		.popular__block
			.popular__block_title
				h2.title_main {{ $parent.$options.props.title }}
				p.title_description {{ $parent.$options.description }}
			.popular__block_cards
				.choose
					.item
						input#for__all_popular.for__all.custom-radio(type='radio' v-model = "current")
						label.for__all_label(for='for__all_popular') Все товары
					.item(v-for = "(item,index) in categories")
						input.for__hunting.custom-radio(:id = "'inp_'+index" type='radio' v-model = "current")
						label.for__hunting_label(:for="inp_'+index") {{ item.title }}
				.choose__mobile Все товары
				.image__slider.swiper-container
					.image__slider_wrapper.swiper-wrapper
						.swiper-slide(v-for="item in sliderProducts")
							Product(:product = "item")
					.swiper-pagination
			a.link__to(href='/Catalog') Больше товаров
</tempalte>

<script>
import Product from '../catalog/components/catalog-item.vue'
import { mapActions, mapGetters } from 'vuex'

export default {
	data(){
		return {
			current: "all"
		}
	},
	components: {
		Product
	},
	computed: {
		...mapGetters('slider', ['popular', 'novelty']),
		sliderProducts(){
			return (this.$parent.options.props.type == 0) 
					? this.novelty
					: this.popular
		}
	},
	watch:{
		current(){
			switch (this.current){
				case: 
			}
		}
	},
	methods: {
		...mapActions('slider', ['fetchItems'])
	},
	created(){
		if (!(this.popular || this.novelty)){
			this.fetchItems({this.$parent.options.props.link, this.$parent.options.props.id})
		}
	}
}

</script>

<style scoped lang = "scss">
</style>