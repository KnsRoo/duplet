<template lang = "pug">
section.liked
	.wrapper
		p.title__page Отложенные товары
		loader(v-if = "!loaded")
		.liked__wrap(v-else)
			FavItem(v-for="item in favorites" :product = "item" :favorite = "true")
</template>

<script>
import ky from 'ky';
import FavItem from '../catalog/components/catalog-item.vue'
import loader from "../loaders/ellipse.vue";
import {mapActions, mapGetters} from 'vuex'

export default {
	data() {
		return {
			loaded: false
		}
	},
	components: {
		FavItem,
		loader
	},
	computed: {
		...mapGetters('favorites', ["favorites"]),
	},
	methods: {
		...mapActions('favorites', ["fetchItems"]),
	},
	async created(){
		await this.fetchItems()
		this.loaded = true
	}
}
</script>