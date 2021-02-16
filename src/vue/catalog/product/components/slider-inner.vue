<template lang = "pug">
.product.discount
	.discount__percent(v-if="product.discount != null || product.discount != 0") {{ product.discount }} %
	.favorite__cross.icon-menu-cancel
	.product__block
		img.product__img(:src="product.picture" alt='')
	.product__content
		.product__content_title
			.product__content_title_name {{ product.title }}
			.product__content_title_price
				.first__price ₽ {{ product.price }}
				.discount__price ₽ {{ product.discount_price }}
		figure.icon-add
</template>

<script>
export default {
	data(){
		return {}
	},
	props: {
		product: Object
	},
}
</script>