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
		.discount__price ₽ {{ product.discount_price }}
		.main__price.closed
			span.main__price_title ₽ {{ product.price }}
		.status
			.available.hidden
				figure.icon-available
				span.available__title В наличии
			.unavailable
				figure.icon-menu-cancel
				span.unavailable__title Нет в наличии
	.card__item_price(v-else)
		.price__cost ₽ {{ product.price }}
	.order__box
		.order__container
			.choose__number_box
				.btn__minus.icon-number
				.choose__number 99
				.btn__plus.icon-number-right
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
    data() {
        return {};
    },
    props: {
        product: Object
    },
    computed: {},
    methods: {},
    created() {}
};
</script>