<template lang="pug">
section.reserved
	.wrapper
		.reserved__box
			.name__box
				.subtitles__thing.name__title Товар
				.subtitles__address.name__title адрес получения
				.subtitles__info.name__title Подробности получения
				.subtitles__cost.name__title Стоимость
				.subtitles__status.name__title Статус заказа
			.reserved__things(v-if = "loaded")
				loader(v-if = "converting")
				ProductItem(v-for="item in orderItems" :order = "item")
				loader(v-if = "!fetched")
			.catalog__next(v-if="orders.next && loaded && fetched")
				.link__to(@click="next") Загрузить еще
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
import ProductItem from './reserved-item.vue'
import loader from "../../loaders/ellipse.vue";

export default {
	data() {
		return {
			loaded: false,
			fetched: true,
			converting: true
		};
	},
	components: {
		loader,
		ProductItem
	},
	computed: {
		...mapGetters("orders", ["orders"]),
		orderItems(){
			let orders = []
			this.orders.items.forEach(val => {
				val.props['Товары'].value.forEach(v => {
					orders.push({
						"Статус": val.props['Статус'].value,
						"Бронь до": val.props['Бронь до'].value,
						"Доступен с": val.props['Доступен с'].value,
						"Товар": v
					})
				})
			})
			this.converting = false
			return orders
		}
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
		await this.fetchOrders('Бронирование')
		this.loaded = true
		this.$emit('toggleLoad', { component: 'reserved', value: true})
	}
};
</script>

<style scoped lang = "scss">
.reserved__box {
	margin-top: 20px;
}
.catalog__next{
	display: flex;
}
</style>
