<template lang = "pug">
section.news
	.wrapper(v-if="loaded")
		.title__page Новости
		loader(v-if = "!loaded")
		newItem(v-if = "loaded" v-for="item in newsChunked" :newsItem = "item")
		loader(v-if="!fetched")
		.news__next(v-if="urls.next && loaded && fetched")
			a.link(@click="next") Загрузить еще
</template>

<script>
import ky from "ky";
import loader from "../loader/index.vue";
import newItem from "./new-item.vue";

export default {
	data() {
		return {
			loaded: false,
			fetched: true,
			first: {},
			news: [],
			urls: {
				prev: null,
				next: null
			}
		};
	},
	components: {
		newItem,
		loader
	},
	computed: {
		newsChunked() {
			let arr = [...this.news],
				result = [];
			let i = parseInt(arr.length / 4) + 1;
			let temp = [...Array(i)];
			temp.forEach(val => {
				result.push(arr.splice(0, 4));
			});
			return result;
		}
	},
	methods: {
		async fetchNews() {
			let result = await ky.get("/api/news/lines").json();
			this.news = result._embedded.items;
			this.urls.next = result._links.next;
			this.loaded = true;
		},
		async next() {
			this.fetched = false
			let result = await ky.get(this.urls.next.href).json();
			result._embedded.items.forEach(val => {
				this.news.push(val);
			});
			this.urls.next = result._links.next;
			this.fetched = true;
		}
	},
	async created() {
		await this.fetchNews();
	}
};
</script>