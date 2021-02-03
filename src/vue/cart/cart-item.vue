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
		.card__important(v-if = "cartItem.status === 'reserved'" @click="showInfo" v-bind:class="{card__important_active: show}")
			.card__important_icon !
			.card__important_text Товар доступен только для резервирования
		.card__img
			.product
				.discount__percent 15%
				.favorite__cross.icon-menu-cancel
				.product__block
					img.product__img(:src="cartItem.picture", alt="Ружьё")
		.card__text
			.card__text_title {{ cartItem.title }}
			.mobile__block_hidden
				.card__number_mobile
					.btn__minus.icon-number(@click="downNumber")
					.choose__number {{number}}
					.btn__plus.icon-number(@click="upNumber")
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
		.btn__minus.icon-number(@click="downNumber")
		.choose__number {{number}}
		.btn__plus.icon-number(@click="upNumber")
	.card__in-all.card__info ₽ {{ fullPrice }}
</template>

<script>
import { mapActions } from "vuex";

export default {
    data() {
        return {
            number: 1,
            price: 0,
            show: false
        };
    },
    computed: {
        fullPrice() {
            return (this.number * this.price).toFixed(2);
        }
    },
    methods: {
        ...mapActions("cart", ["removeFromCart", "updateItemCount"]),
        async upNumber() {
            this.number++;
            await this.updateItemCount({ id: this.$props.cartItem.id, count: this.number})
        },
        async downNumber() {
            if (this.number > 1) {
                this.number--;
            }
            await this.updateItemCount({ id: this.$props.cartItem.id, count: this.number})
        },
        showInfo() {
            this.show = !this.show;
        }
    },
    props: {
        cartItem: Object
    },
    created(){
    	this.number = parseInt(this.$props.cartItem.count)
    	this.price  = parseFloat(this.$props.cartItem.price)
    	console.log(this.$props.cartItem)
    }
};
</script>