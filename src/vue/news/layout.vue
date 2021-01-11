<template lang = "pug">
section.news
	.wrapper(v-if="loaded")
		.news__title Новости
		.news__block
			img.news__block_img(:src="first.picture" alt='')
			.news__block_instruction
				.news__block_instruction_flex
					.instruction__title {{ first.title }}
					.instruction__text {{ first.announce }}
					a.instruction__link(:href='first.pageRef') Читать
		.news__cards
			newItem(v-for="item in news" :newsItem = "item")
		.news__next
			a.link(v-if="urls.next" @click="next") Загрузить еще
</template>

<script>
import ky from 'ky'
import newItem from './new-item.vue'

export default {
	data() {
		return {
			loaded: false,
			first: {},
			news: [],
			urls: {
				prev: null,
				next: null
			}
		}
	},
	components: {
		newItem
	},
	methods: {
		async fetchNews(){
			let result = await ky.get('/api/news/lines').json()
			this.news = result._embedded.items
			this.first = this.news.shift()
			this.urls.next = result._links.next
			this.loaded = true
		},
		async next(){
			let result = await ky.get(this.urls.next.href).json()
			result._embedded.items.forEach(val => {
				this.news.push(val)
			})
			console.log(this.news)
			this.urls.next = result._links.next
		}
	},
    async created() {
        await this.fetchNews();
    },
}
</script>