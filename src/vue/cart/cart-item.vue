<template lang = "pug">
.card
	.card__act
		.card__bookmark
			img.card__icon(src='/assets/img/icons/bookmark.svg' alt='')
			.card__title Отложить
		.card__delete(@click = "removeFromCart(cartItem.id)")
			img.card__icon(src='/assets/img/icons/delete.svg' alt='')
			.card__title Удалить
	.card__des
		.card__img
			img(:src="cartItem.picture" alt='')
		card__text
			.card__text_title 
				.card__text_title_subtitle {{ cartItem.title }}
				.card__important(v-if = "cartItem.status === 'reserved'")
					.card__important_icon !
					.card__important_text Товар доступен только для резервирования
	.card__discount 100%
	.card__price ₽ {{ cartItem.price }}
	.card__number {{ cartItem.count }}
	.card__in-all
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