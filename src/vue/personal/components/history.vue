<template lang="pug">
section.reserved
	.wrapper(v-if = "loaded")
		.reserved__title История покупок
		OrderItem(v-for="item in orders.items" :orderProps = "item.props")
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import OrderItem from './history-item.vue'

export default {
	data() {
		return {
			loaded: false
		};
	},
	components: {
		OrderItem
	},
	computed: {
		...mapGetters("orders", ["orders"])
	},
	methods: {
		...mapActions("orders", ["fetchOrders", "fetchNextOrders"])
	},
	async created(){
		await this.fetchOrders('Покупка')
		this.loaded = true
		this.$emit('toggleLoad', { component: 'history', value: true})
	}
}
</script>