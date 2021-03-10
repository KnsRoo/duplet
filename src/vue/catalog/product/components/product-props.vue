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
		.main__price
			span.main__price_title ₽ {{ product.price }}
		.status
			.available.hidden
				figure.icon-available
				span.available__title В наличии
			.unavailable
				figure.icon-menu-cancel
				span.unavailable__title Нет в наличии
	.order__box
		.order__container
			.choose__number_box
				.btn__minus.icon-number(@click = "rem")
				.choose__number {{ count }}
				.btn__plus.icon-number-right(@click = "add")
			.btn.btn__to.to__cart( @click = "addToCart({ id: product.id, count})")
				span.btn__name в корзину
		.to__favorite(@click="addFavorite")
			figure.icon-liked(v-if ="favorite === false" )
			figure.icon-liked-fill(v-else)
			span.to__favorite_title Добавить в избранное
	.card__item_category
		.category__title Категория:
		.category__name {{ product.category.title }}
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import authfetch from '../../../../js/components/authfetch'

export default {
    data() {
        return {
            count: 1,
            favorite: false
        };
    },
    props: {
        product: Object
    },
    computed: {},
    methods: {
        ...mapActions("cart", [
            "removeFromCart",
            "addToFavorites",
            "addToCart"
        ]),
        add() {
            this.count++;
        },
        rem() {
            if (this.count > 1) {
                this.count--;
            }
        },
        addFavorite() {
            this.favorite = !this.favorite;
        }
    },
    async created() {
        const response = await authfetch(`${window.location.origin}/api/favorites/${this.$props.product.id}`, {
            method: 'GET',
        }) 
        if (response.ok){
        	let data = await response.json()
        	this.favorite = data.favorite
        } 

    }
};
</script>