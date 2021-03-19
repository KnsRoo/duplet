<template lang = "pug">
.wrapper
	h3.title Категории товаров
	.category__wrapper
		a.category(v-for = "cat in cats")
			.category__inner
				img.category__image(v-if = "cat.picture" :src = "cat.picture")
				img.category__image(v-else src = "/assets/img/default.png")
			.category__title_wrapper
				.category__title {{ cat.title.toUpperCase() }}
			.category__count 1432 Товаров
	center
		input.button_load(v-if = "limit" type = "button" value = "Показать еще" @click = "toggleLimit")
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
	data(){
		return {
			limit: true
		}
	},
	computed: {
		...mapGetters("catalog", ["catalogGroups"]),
		cats(){
			return (this.limit) ? this.catalogGroups.slice(0,12) : this.catalogGroups
		}
	},
	methods: {
		...mapActions('catalog', ['fetchCatalogGroups']),
		toggleLimit(){
			this.limit = !this.limit
		}
	},
	async created(){
        await this.fetchCatalogGroups(this.$parent.options.link);
	}
}

</script>

<style scoped lang = "scss">

.title {
	text-align: center;
	font-weight: 400;
	font-size: 72px;
	line-height: 125%;
	margin-bottom: 40px;
}

.button_load {
  background: #e0e0e0;
  border: none;
  text-align: center;
  line-height: 46px;
  width: 140px;
  height: 46px;
  color: #888888;
  cursor: pointer;
  position: relative;
  transition: 0.25s;
  font-family: FuturaBookC;	
  margin-top: 66px;
  margin-bottom: 90px;
}

.button_load:hover {
    box-shadow: inset 0 -3.25em 0 0 #333;
    color: #fff;
    transition: 0.25s;
    font-family: FuturaBookC;
}

.category {
	&__inner {
		display: flex;
		background: rgba(242, 242, 242, 0.7);
		border: 1px solid #e0e0e0;
		flex-direction: column;
		align-items: center;
		text-align: center;
		width: 100%;
		height: 360px;		
	}

	&__title {
		font-family: FuturaBookC;
		color: #333333;
		font-weight: 400;
		font-size: 20px;
		cursor: pointer;	
		
		&_wrapper {
			display: flex;
			margin-top: 70px;
			height: 46px;
			align-items: center;		
		}	
	}

	&__count {
		font-family: FuturaBookC;
		color: #333333;
		margin-top: 18px;			
	}

	&__image {
		margin: 17px 13px 0px 12px;
		width: 175px;
		height: 175px;		
	}

	&__wrapper {
		display: grid;
		grid-gap: 30px;
		grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;	
	}
}

@media(max-width: 1580px){
	.category__wrapper {
		grid-template-columns: 1fr 1fr 1fr 1fr;
	}		
}
@media(max-width: 1024px){
	.category__wrapper {
		grid-template-columns: 1fr 1fr 1fr;
	}		
}
@media(max-width: 768px){
	.category__wrapper {
		grid-template-columns: 1fr 1fr;
	}		
}
@media(max-width: 500px){
	.category__wrapper {
		grid-template-columns: 1fr;
	}		
}

</style>