<template lang = "pug">
.product.favorite
	.discount__percent 15%
	.favorite__cross.icon-menu-cancel(@click = "removeItem(item.id)")
	.product__block
		img.product__img(:src="item.picture")
	.product__content
		.product__content_title
			.product__content_title_name {{ item.title }}
			.product__content_title_price
				.first__price ₽ {{ item.price }}
				.discount__price ₽ 34 999
		figure.icon-add(@click = "addToCart(item.id)")
</template>

<script>
import ky from 'ky';
import {mapActions, mapGetters} from 'vuex'

export default {
	data() {
		return {
		}
	},
	props: {
		item: Object
	},
	methods: {
		...mapActions('favorites', ["removeItem"]),
		...mapActions('cart', ["addToCart"])
	},
}
</script>