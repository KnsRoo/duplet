<template lang = "pug">
section.liked
	.wrapper
		p.liked__title Отложенные товары
		.liked__wrap(v-if = "loaded")
			FavItem(v-for="item in favorites" :item = "item")
</template>

<script>
import ky from 'ky';
import FavItem from './fav-item.vue'
import {mapActions, mapGetters} from 'vuex'

export default {
	data() {
		return {
			loaded: false
		}
	},
	components: {
		FavItem
	},
	computed: {
		...mapGetters('favorites', ["favorites"]),
	},
	methods: {
		...mapActions('favorites', ["fetchItems"]),
	},
	async created(){
		await this.fetchItems()
		console.log('fetched')
		this.loaded = true
	}
}
</script>