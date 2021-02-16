<template lang = "pug">
.profile
	a.icon-profile.js-login-btn
	a.icon-liked.js-login-btn(href = "/favorite")
		.icon-liked_number(:class = "{gray: count == 0}") {{ count }}
	a.icon-basket(href = "/cart")
		.icon-basket_number(:class = "{gray: getCartStat.countTotal == 0}") {{ getCartStat.countTotal }}
		.js
</template>

<script>
import ky from 'ky';
import authfetch from '../../js/components/authfetch'
import { mapActions,mapGetters } from 'vuex'

export default {
	data() {
		return {
			show: false
		}
	},
	computed: {
		...mapGetters('favorites', ["count"]),
		...mapGetters('cart', ["getCartStat"])
	},
	methods: {
		...mapActions({
			'fetchCartCount' : 'cart/fetchItems',
			'fetchFavCount' : 'favorites/fetchItems',
		}),
		async update(){
		  	this.fetchCartCount()
		  	this.fetchFavCount()
		}
	},
	async created(){
		this.update()
	}
}
</script>