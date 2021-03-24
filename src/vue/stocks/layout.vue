<template lang = "pug">
section.discount
	.wrapper
		.title__page Акции
		loader(v-if = "!loaded")
		.discount__items(v-if = "loaded")
			stockItem(v-if = "loaded" v-for="item in stocks" :item = "item")
		loader(v-if="!fetched")
		.news__next(v-if="urls.next && loaded && fetched")
			a.link(@click="next") Загрузить еще
</template>

<script>
import ky from "ky";
import loader from "../loaders/ellipse.vue";
import stockItem from "./stock-item.vue";

export default {
	data() {
		return {
			loaded: false,
			fetched: true,
			stocks: [],
			urls: {
				prev: null,
				next: null
			}
		};
	},
	components: {
		stockItem,
		loader
	},
	methods: {
		async fetchStocks() {
			let result = await ky.get(this.$parent.$options.link).json();
			this.stocks = result._embedded.items;
			this.urls.next = result._links.next;
			this.loaded = true;
		},
		async next() {
			this.fetched = false
			let result = await ky.get(this.urls.next.href).json();
			result._embedded.items.forEach(val => {
				this.stocks.push(val);
			});
			this.urls.next = result._links.next;
			this.fetched = true;
		}
	},
	async created() {
		await this.fetchStocks();
	}
};
</script>
