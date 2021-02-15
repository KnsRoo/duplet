<template lang = "pug">
.card__inner
	.title__box
		.title__description Описание
		.title__characteristic Характеристики
	.card__description
		p.card__description_text(ref="desc")
	.card__characteristic.hidden
		.characteristic__brand.characteristic__box(v-if = "brand")
			.characteristic__title Бренд:
			.characteristic__desc {{ brand }}
		.characteristic__model.characteristic__box
			.characteristic__title Модель:
			.characteristic__desc 34Р
		.characteristic__weight.characteristic__box
			.characteristic__title Вес изделия, гр:
			.characteristic__desc ~ 3300
		.characteristic__caliber.characteristic__box
			.characteristic__title Калибр
			.characteristic__desc 12/70
		.characteristic__much_stems.characteristic__box
			.characteristic__title Количество стволов:
			.characteristic__desc Двуствольное
		.characteristic__where_stems.characteristic__box
			.characteristic__title Расположение стволов:
			.characteristic__desc Вертикальное
		.characteristic__width.characteristic__box
			.characteristic__title Длина ствола, мм:
			.characteristic__desc 710
		.characteristic__how.characteristic__box
			.characteristic__title Принцип действия:
			.characteristic__desc Переломное
		.characteristic__material.characteristic__box
			.characteristic__title Материал приклада:
			.characteristic__desc Орех
		.characteristic__country.characteristic__box
			.characteristic__title Страна бренда:
			.characteristic__desc Россия
		.characteristic__madein.characteristic__box
			.characteristic__title Страна производитель:
			.characteristic__desc Россия

</template>

<script>
export default {
	data(){
		return {}
	},
	props: {
		product: Object
	},
	computed: {
		brand(){
			let brand = this.$props.product.props["Бренд"]
			if (brand) return brand.value
			 return false
		}

	},
	methods: {

	},
	mounted(){
		this.$refs.desc.innerHTML = this.$props.product.about
	},
	created(){
		
	}
}
</script>