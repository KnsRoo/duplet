<template lang = "pug">
.card__benefits
	.card__title
		.card__title_name {{ product.title }}
		.card__title_brand
			.brand__title Бренд:
			.brand__name Тульский оружейный завод
	h1.card__item_name {{ product.title }}
	p.card__item_text {{ product.preview }}
	.card__item_price(v-if = "product.discount != null && product.discount != 0")
		.price__discount ₽ {{ product.price }}
		.price__box
			.price__cost ₽ {{ product.discount_price }}
			.price__available В наличии
	.card__item_price(v-else)
		.price__cost ₽ {{ product.price }}
	.order__box
		.order__container
			input.choose__number(type='number' min='1' max='50')
			button.to__cart.add__to__cart(type='submit') в корзину
		.to__favorite
			figure.icon-liked
			span.to__favorite_title Добавить в избранное
	.card__item_category
		.category__title Категория:
		.category__name {{ product.category.title }}
</template>

<script>
export default {
	data(){
		return {}
	},
	props: {
		product: Object,
	},
	computed: {

	},
	methods: {

	},
	created(){

	}
}
</script>