<template lang = "pug">
.profile
	a.icon-profile.js-login-btn
	a.icon-liked(href = "/favorite")
		.icon-liked_number(:class = "{gray: liked_cnt == 0}") {{ liked_cnt }}
	a.icon-basket(href = "/cart")
		.icon-basket_number(:class = "{gray: cart_cnt == 0}") {{ cart_cnt }}
		.js
</template>

<script>
import ky from 'ky';

export default {
	data() {
		return {
			liked_cnt: 0,
			cart_cnt: 0
		}
	},
	methods: {
		async update(){
		  	let result = await ky.get(`${window.location.origin}/api/cart/count`).json()
			if (result.count) {
				this.cart_cnt = result.count
			}	
		}
	},
	async created(){
		this.update()
	}
}
</script>