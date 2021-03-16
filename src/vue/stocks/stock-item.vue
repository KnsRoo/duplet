<template lang = "pug">
.discount__item(:style = "{ background }")
	.discount__box
		.block__wrap
			.discount__box_title {{ item.title }}
			.discount__box_text {{ item.announce }}
			a.discount__box_link(:href="good") Купить сейчас
		.img__wrap
			img.discount__box_img(:src = "image")
</template>

<script>
import ky from "ky";

export default {
    data() {
        return {
        	extra: undefined
        };
    },
    computed: {
    	background(){
    		let colors = this.extra["Цвет"].value.colors.filter(val => {
    			val.checked == true
    		})
    		let color = "#fff"
    		if (colors){
    			color = colors[0];
    		}
    		if (color.type == 'static'){
    			return color.color
    		} else {
    			return `linear-gradient(${color.angle}deg,${color.color1},${color.color2})`
    		}
    	},
    	image(){
    		let images = this.extra['Изображение'].value.value
    		if (images){
    			return images[0]
    		}
    		return '/assets/img/tent.png'
    	},
    	good(){
    		let good = this.extra['Товар'].value.value
    		if (good){
    			return good[0]
    		}
    		return '/Catalog'
    	}
    },
    props: {
        item: Object
    },
    async created(){
    	let response = await ky.get(this.$props.item._links.props).json()
    	this.extra = response._embedded.props
    }
};
</script>