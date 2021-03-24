<template lang = "pug">
.category__content
	h3.title Категории товаров
	.category__wrapper
		a.category(v-for = "cat in cats" :href = "`/Catalog?group=${cat.id}`")
			.category__inner
				img.category__image(v-if = "cat.picture" :src = "cat.picture")
				img.category__image(v-else src = "/assets/img/default.png")
				.category__title_wrapper
					.category__title {{ cat.title.toUpperCase() }}
				.category__count(v-if = "counts[cat.id]") {{ counts[cat.id] }}
				loader(v-else)
	//-input.button_load(v-if = "limit" type = "button" value = "Показать еще" @click = "toggleLimit")
</template>

<script>
import ky from 'ky'
import { mapActions, mapGetters } from 'vuex'
import loader from '../../loaders/line.vue'

export default {
	data(){
		return {
			limit: false,
			loaded: false,
			counts: {},
		}
	},
	components: {
		loader
	},
	computed: {
		...mapGetters("catalog", ["catalogGroups", "catalogCounts"]),
		cats(){
			return (this.limit) ? this.catalogGroups.slice(0,12) : this.catalogGroups
		}
	},
	methods: {
		...mapActions('catalog', ['fetchCatalogGroups', 'fetchCatalogCounts']),
		toggleLimit(){
			this.limit = !this.limit
		},
		format(count){

		  if ((count.toString().endsWith('11')) ||
		  (count.toString().endsWith('12')) ||
		  (count.toString().endsWith('13')) ||
		  (count.toString().endsWith('14'))){
		  	return `${count} Товаров`
		  }

		  switch (count % 10){
		    case 1: return `${count} Товар`; break;
		    case 2:
		    case 3:
		    case 4: return `${count} Товара`; break;
		    case 0:
		    case 5:
		    case 6:
		    case 7:
		    case 8:
		    case 9: return `${count} Товаров`; break;
		  }

		  return `${count} Товаров`

		},
	},
	async created(){
        await this.fetchCatalogGroups(this.$parent.$options.link);
		await this.fetchCatalogCounts();
		Object.entries(this.catalogCounts).forEach(([k,v]) => {
			this.$set(this.counts, k, this.format(v))
		})
        this.loaded = true
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

  &:hover {
  	box-shadow: inset 0 -3.25em 0 0 #333;
    color: #fff;
    transition: 0.25s;
  }
}

.category {

	&__content {
		display: flex;
		width: 82.3%;
		margin: 0 auto;
		justify-content: center;
		flex-direction: column;
		align-items: center;
		margin-bottom: 80px;
	}

	&__inner {
		display: flex;
		background: rgba(242, 242, 242, 0.7);
		border: 1px solid #e0e0e0;
		flex-direction: column;
		align-items: center;
		text-align: center;
		width: 100%;
		height: 360px;	

		&:hover {
			background: #e0e0e0;
		}	
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

@media(max-width: 1480px){
	.category__wrapper {
		grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
		grid-gap: 20px;
	}		
}
@media(max-width: 1224px){
	.category__wrapper {
		grid-template-columns: 1fr 1fr 1fr 1fr;
		grid-gap: 15px;
	}		
}
@media(max-width: 868px){
	.category__wrapper {
		grid-template-columns: 1fr 1fr 1fr;
	}		
}
@media(max-width: 500px){
	.category__wrapper {
		grid-template-columns: 1fr 1fr;
		grid-gap: 10px;
	}		
}

</style>
