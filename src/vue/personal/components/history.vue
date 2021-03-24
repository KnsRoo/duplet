<template lang="pug">
section.reserved
	.wrapper(v-if = "loaded")
		OrderItem(v-for="item in orders.items" :orderProps = "item.props")
		loader(v-if = "!fetched")
		.catalog__next(v-if="orders.next && loaded && fetched")
			.link__to(@click="next") Загрузить еще
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import OrderItem from './history-item.vue'
import loader from "../../loaders/ellipse.vue";

export default {
	data() {
		return {
			loaded: false,
			fetched: true
		};
	},
	components: {
		OrderItem,
		loader
	},
	computed: {
		...mapGetters("orders", ["orders"])
	},
	methods: {
		...mapActions("orders", ["fetchOrders", "fetchNextOrders"]),
		async next() {
			this.fetched = false;
			await this.fetchNextOrders();
			this.fetched = true;
		},
	},
	async created(){
		await this.fetchOrders('Покупка')
		this.loaded = true
		this.$emit('toggleLoad', { component: 'history', value: true})
	}
}
</script>

<style lang = "scss">
.catalog__next{
	display: flex;
}
</style>
