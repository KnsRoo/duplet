<template lang = "pug">
.cart__booked_box
	.card
		.card__act
			.card__favorite(@click = "addToFavorites(item.id)")
				figure.card__icon.icon-liked
				.card__title Отложить
			.card__delete(@click = "removeFromCart(item.id)")
				figure.card__icon.icon-delete
				.card__title Удалить
		.card__des.card__info
			.card__img
				.product
					.discount__percent 15%
					.favorite__cross.icon-menu-cancel
					.product__block
						img.product__img(:src="item.picture" alt="")
			.card__text
				.card__text_title {{ item.title }}
				.mobile__block_hidden
					.card__number_mobile
						.btn__minus.icon-number
						.choose__number 1
						.btn__plus.icon-number
					.card__in-all__mobile ₽ {{ item.price }}
				.card__text_block
					.card__brand
						.card__brand_title Бренд:
						.card__brand_name ТОЗ
					.card__caliber
						.card__caliber_title Калибр:
						.card__caliber_name 12/70
		.card__number.card__info
			.btn__minus.icon-number(@click = "rem")
			.choose__number {{ count }}
			.btn__plus.icon-number(@click = "add")
		.card__in-all.card__info ₽ {{ item.price }}
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
	data(){
		return {
			count: 1
		}
	},
	methods: {
		...mapActions("cart", ["removeFromCart", "updateItemCount", "addToFavorites"]),
		...mapActions("favorites", ["appendItem"]),
		async add() {
			this.count++;
			await this.updateItemCount({ id: this.$props.item.id, count: this.count})
		},
		async addToFavorites(itemId){
			if (await this.appendItem(itemId)){
				this.removeFromCart(itemId)
			}
		},
		async rem() {
			if (this.count > 1) {
				this.count--;
			}
			await this.updateItemCount({ id: this.$props.item.id, count: this.count})
		},
	},
	props: {
		item: Object
	}
}
</script>