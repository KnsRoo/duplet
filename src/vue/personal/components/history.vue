<template lang="pug">
article.buy__history
	.wrapper
		.history__title История покупок
		input.history__input
		.history__box
			Item(v-for="item in orders")
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import Item from './order-item.vue'

export default {
	data() {
		return {
		};
	},
	computed: {
		...mapGetters("orders", ["orders"])
	},
	methods: {
		...mapActions("orders", ["fetchOrders", "fetchNextOrders"])
	},
	async created(){
		this.$emit('toggleLoad', { component: 'history', value: true})
	}
}
</script>