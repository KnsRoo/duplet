<template lang="pug">
article.buy__history
	.wrapper
		.history__title История покупок
		OrderItem(v-for="item in orders.items" :orderProps = "item.props")
		//- input.history__input
		//- .history__box
		//- 	Item(v-for="item in orders")
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import OrderItem from './history-item.vue'

export default {
	data() {
		return {
		};
	},
	components: {
		OrderItem
	},
	computed: {
		...mapGetters("user", ["orders"])
	},
	methods: {
		...mapActions("user", ["fetchOrders", "fetchNextOrders"])
	},
	async created(){
		await this.fetchOrders('Покупка')
		console.log(this.orders.items)
		this.$emit('toggleLoad', { component: 'history', value: true})
	}
}
</script>