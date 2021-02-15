<template lang = "pug">
.product_wrapper(v-if = "loaded")
	Popup(v-if = "showPopup")
	section.card__item
		.wrapper
			.card__box
				.card__item_name_mobile {{ product.title }}
				ImagesSlider(v-if = "images" :images = "images")
				.slider__box(v-else)
				ProductProps(:product = "product")
			.card__wrapper
				Description(:product = "product")
				article.card__seen(v-if = "chainProducts")
					.card__seen_title С этим товаром смотрят
					ChainProductsSlider(v-if = "chainProducts" :links = "chainProducts")
					a.link__to(href='/catalog') Смотреть все
	.wrapper
		article.similar__things(v-if = "similarProducts")
			.similar__things_title Похожие товары
			SimilarProductsSlider(v-if = "similarProducts" :links = "similarProducts")
			a.link__to(href='/catalog') Смотреть всё
	.wrapper
		article.recently__seen(v-if = "!emptySeen")
			.recently__seen_title Вы недавно просматривали
			SeenProductsSlider
			a.link__to(href='/catalog') Смотреть всё
</template>

<script>
import Seen from '../../../js/components/seenStore'
import Popup from "./components/popup.vue";
import ImagesSlider from "./components/images-slider.vue";
import ProductProps from "./components/product-props.vue";
import Description from "./components/product-description.vue";
import ChainProductsSlider from "./components/chain-products-slider.vue";
import SimilarProductsSlider from "./components/similar-products-slider.vue";
import SeenProductsSlider from "./components/seen-products-slider.vue";

import { mapActions, mapGetters } from "vuex";

export default {
	data() {
		return {
			showPopup: false,
			loaded: false
		};
	},
	components: {
		Popup,
		ImagesSlider,
		ProductProps,
		Description,
		ChainProductsSlider,
		SimilarProductsSlider,
		SeenProductsSlider
	},
	computed: {
		...mapGetters("catalog", ["product"]),
		chainProducts(){
			let chain = this.product.props["с этим товаром покупают"]
			if (chain) return chain.value
			return false
		},
		similarProducts(){
			let chain = this.product.props["похожие товары"]
			if (chain) return chain.value
			return false
		},
		emptySeen(){
			return new Seen('seenList').isEmpty()
		},
		images(){
			let images = this.product.props["изображения"]
			if (images) {
				images.value.push(this.product.picture)
				return images.value
			}
			return false
		},

	},
	methods: {
		...mapActions("catalog", ["fetchProduct"])
	},
	async created() {
		await this.fetchProduct(this.$parent.$options.link);
		this.loaded = true;
		new Seen('seenList').push(this.$parent.$options.link)
	}
};
</script>