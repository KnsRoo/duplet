<template lang = "pug">
.card
	.card__act
		.card__favorite(@click = "addToFavorites(cartItem.id)")
			figure.icon-liked(v-if ="favorite === false" )
			figure.icon-liked-fill(v-else)
			.card__title Отложить
		.card__delete(@click = "removeCart(cartItem.id)")
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
					img.product__img(:src="cartItem.picture", alt="")
		.card__text
			.card__text_title {{ cartItem.title }}
			.mobile__block_hidden
				.card__number_mobile
					.btn__minus.icon-number(@click="downNumber")
					.choose__number {{number}}
					.btn__plus.icon-number(@click="upNumber")
				.card__in-all__mobile ₽ {{ fullPrice }}
			.card__text_block
				.card__brand
					.card__brand_title Бренд:
					.card__brand_name ТОЗ
				.card__caliber
					.card__caliber_title Калибр:
					.card__caliber_name 12/70
	.card__discount.card__info(v-if = "cartItem.discount") {{ (cartItem.discount !== '%') ? cartItem.discount : '0%' }}
	.card__discount.card__info(v-else)
	.card__price.card__info ₽ {{ cartItem.price.toFixed(2) }}
	<!-- .card__number.card__info {{ cartItem.count }} -->
	.card__number.card__info
		.btn__minus.icon-number(@click="downNumber")
		.choose__number {{number}}
		.btn__plus.icon-number(@click="upNumber")
	.card__in-all.card__info ₽ {{ fullPrice }}
</template>

<script>
import { mapActions } from "vuex";
import noty from '../../js/components/noty'
import authfetch from '../../js/components/authfetch'

export default {
	data() {
		return {
			number: 1,
			price: 0,
			discountPrice: null,
			show: false,
			favorite: false
		};
	},
	computed: {
		fullPrice() {
			let price = (this.discountPrice) ? this.discountPrice : this.price
			return (this.number * price).toFixed(2);
		}
	},
	methods: {
		...mapActions("cart", ["removeFromCart", "updateItemCount", "addToFavorites"]),
		...mapActions("favorites", ["appendItem"]),
		async upNumber() {
			this.number++;
			await this.updateItemCount({ id: this.$props.cartItem.id, count: this.number})
		},
		async removeCart(itemId){
			await this.removeFromCart(itemId)
			noty('sucсess', 'Товар удален из корзины')
		},
		async addToFavorites(itemId){
			if (!this.favorite){
				if (await this.appendItem(itemId)){
					this.removeFromCart(itemId)
				}
				noty('sucсess', 'Товар добавлен в избранное')
			}
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
	async created(){
		this.number = parseInt(this.$props.cartItem.count)
		this.price  = parseFloat(this.$props.cartItem.price)
		this.discountPrice  = parseFloat(this.$props.cartItem.discount_price)
		try {
			const response = await authfetch(`${window.location.origin}/api/favorites/${this.$props.cartItem.id}`, {
	            method: 'GET',
	        }) 
	        if (response.ok){
	        	let data = await response.json()
	        	this.favorite = data.favorite
	        }
		} catch (e){

		}
	}
};
</script>
