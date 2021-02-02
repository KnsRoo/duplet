<template lang = "pug">
.card
	.card__act
		.card__favorite
			figure.card__icon.icon-liked
			.card__title Отложить
		.card__delete(@click = "removeFromCart(cartItem.id)")
			figure.card__icon.icon-delete
			.card__title Удалить
	.card__des.card__info
		.card__important(v-if = "cartItem.status === 'reserved'")
			.card__important_icon !
			.card__important_text Товар доступен только для резервирования
		.card__img
			.product
				.discount__percent 15%
				.favorite__cross.icon-menu-cancel
				.product__block
					img.product__img(src=("/assets/img/gun.png"), alt="Ружьё")
		.card__text
			.card__text_title {{ cartItem.title }}
			.mobile__block_hidden
				.card__number_mobile
					input.button__minus#button__minus(type="button" value="-")
					label.label__minus.icon-number(for="button__minus")
					input.choose__number(type="number" step="1" min="1" max="9999" value="1")
					input.button__plus#button__plus(type="button" value="+")
					label.label__plus.icon-number(for="button__plus")
				.card__in-all__mobile ₽99 739 000
			.card__text_block
				.card__brand
					.card__brand_title Бренд:
					.card__brand_name ТОЗ
				.card__caliber
					.card__caliber_title Калибр:
					.card__caliber_name 12/70
	.card__discount.card__info 100%
	.card__price.card__info ₽ {{ cartItem.price }}
	<!-- .card__number.card__info {{ cartItem.count }} -->
	.card__number.card__info
		input.button__minus#button__minus(type="button" value="-")
		label.label__minus.icon-number(for="button__minus")
		input.choose__number(type="number" step="1" min="1" max="9999" value="1")
		input.button__plus#button__plus(type="button" value="+")
		label.label__plus.icon-number(for="button__plus")
	.card__in-all.card__info {{ fullPrice }}
</template>

<script>
import { mapActions } from "vuex";

export default {
    data() {
        return {};
    },
    computed: {
        fullPrice: function() {
            const count = parseInt(this.$props.cartItem.count);
            const price = parseFloat(this.$props.cartItem.price);
            return count * price;
        }
    },
    methods: {
        ...mapActions("cart", ["removeFromCart"])
    },
    props: {
        cartItem: Object
    }
};
</script>