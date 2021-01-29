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
		.card__img
			.product.discount
				.discount__percent 15%
				.favorite__cross.icon-menu-cancel
				.product__block
					img.product__img(src=("/assets/img/gun.png"), alt="Ружьё")
		card__text
			.card__text_title {{ cartItem.title }}
			.mobile__block_hidden
				.card__number_mobile 100
				.card__in-all__mobile
					.card__in-all__mobile_price ₽99 739 000
					.card__in-all__mobile_discount ₽99 739 00
			.card__text_block
				.card__brand
					.card__brand_title Бренд:
					.card__brand_name ТОЗ
				.card__caliber
					.card__caliber_title Калибр:
					.card__caliber_name 12/70
		.card__important(v-if = "cartItem.status === 'reserved'")
			.card__important_icon !
			.card__important_text Товар доступен только для резервирования
	.card__discount.card__info 100%
	.card__price.card__info ₽ {{ cartItem.price }}
	.card__number.card__info {{ cartItem.count }}
	.card__in-all.card__info
		.card__in-all_price ₽ {{ fullPrice }}
		.card__in-all_discount
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