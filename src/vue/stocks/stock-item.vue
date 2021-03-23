<template lang = "pug">
.discount__item(:style = "{ background }")
	.discount__box(v-if = "loaded")
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
            loaded: false,
        	extra: undefined
        };
    },
    computed: {
    	background(){
            if (!this.extra) return ''
    		let colors = this.extra["цвет"].value.colors.filter(val => val.checked)
    		let color = "#fff"
    		if (colors){
    			color = colors[0];
    		} else {
                return color
            }
    		if (color.type == 'static'){
    			return color.color
    		} else {
    			return `linear-gradient(${color.angle}deg,${color.color1},${color.color2})`
    		}
    	},
    	image(){
            if (!(this.extra && this.extra['Изображение'])) return '/assets/img/tent.png'
    		let images = this.extra['Изображение'].value.value
    		if (images){
    			return images[0]
    		}
    		return '/assets/img/tent.png'
    	},
    	good(){
            if (!(this.extra && this.extra['Товар'])) return '/Catalog'
    		let good = this.extra['Товар'].value
    		if (good){
    			return good[0].pageRef
    		}
    		return '/Catalog'
    	}
    },
    props: {
        item: Object
    },
    async created(){
    	let response = await ky.get(this.$props.item._links.props.href).json()
    	this.extra = response._embedded.props
        this.loaded = true
    }
};
</script>