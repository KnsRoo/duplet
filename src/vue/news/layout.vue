<template lang = "pug">
section.news
	.wrapper(v-if="loaded")
		.news__title Новости
		newItem(v-for="item in newsChunked" :newsItem = "item")
		.news__next
			a.link(v-if="urls.next" @click="next") Загрузить еще
</template>

<script>
import ky from "ky";
import newItem from "./new-item.vue";

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
        };
    },
    components: {
        newItem
    },
    computed: {
        newsChunked(){
            let arr = [...this.news],
                result = [];
            let i = parseInt(arr.length / 4)+1
            let temp = [...Array(i)]
            temp.forEach(val => {
                result.push(arr.splice(0,4))
            })
            return result        
        }
    },
    methods: {
        async fetchNews() {
            let result = await ky.get("/api/news/lines").json();
            this.news = result._embedded.items
            this.urls.next = result._links.next;
            this.loaded = true;
        },
        async next() {
            let result = await ky.get(this.urls.next.href).json();
            result._embedded.items.forEach(val => {
                this.news.push(val);
            });
            this.urls.next = result._links.next;
        }
    },
    async created() {
        await this.fetchNews();
    }
};
</script>