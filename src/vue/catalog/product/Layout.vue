<template lang = "pug">
.product_wrapper(v-if = "loaded")
	Popup(v-if = "showPopup")
	section.card__item
		.wrapper
			.card__box
				.card__item_name_mobile {{ product.title }}
				ImagesSlider(:images = "images")
				ProductProps(:product = "product")
			.card__wrapper
				Description(:product = "product")
				article.card__seen
					.card__seen_title С этим товаром смотрят
					ChainProductsSlider(:products = "chainProducts")
					a.link__to(href='/catalog') Смотреть все
	.wrapper
		article.similar__things
			.similar__things_title Похожие товары
			SimilarProductsSlider(:products = "similarProducts")
			a.link__to(href='/catalog') Смотреть всё
	.wrapper
		article.recently__seen
			.recently__seen_title Вы недавно просматривали
			SeenProductsSlider(:products = "seenProducts")
			a.link__to(href='/catalog') Смотреть всё
</template>

<script>
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
            chainProducts: [],
            similarProducts: [],
            seenProducts: [],
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
        ...mapGetters("catalog", ["product"])
    },
    methods: {
        ...mapActions("catalog", ["fetchProduct"])
    },
    async created() {
        await this.fetchProduct(this.$parent.$options.link);
        this.loaded = true;
    }
};
</script>