<template lang = "pug">
.card__inner
	.title__box
		.title__description Описание
		.title__characteristic Характеристики
	.card__description
		p.card__description_text
			| Бескурковое двуствольное охотничье ружьё с вертикально
			| расположенными стволами 12, 16, 20 и 28-го калибров. Явилось первой отечественной
			| моделью серийного бокфлинта, имеющего малый вес. Ружья отличаются современными строгими
			| формами, имеют хорошие баланс и прикладистость и высокую кучность боя. Ружья малых
			| калибров имеют такие же размеры колодки, как и у ружей 12-го калибра.
		p.card__description_text
			| Стволы ружья отъёмные, расположены в вертикальной
			| плоскости, имеют стандартные дульные сужения: нижний ствол — получок, верхний — чок.
		p.card__description_text
			| Ударно-спусковой механизм с внутренними курками и двумя
			| спусками расположен на отдельном основании.
		p.card__description_text
			| Взведение курков осуществляется в процессе открывания
			| стволов. Для исключения случайных выстрелов имеются перехватыватели курков и
			| неавтоматический предохранитель. Извлечение стреляных гильз в ружьях ТОЗ-34, ТОЗ-34-20,
			| ТОЗ-34-28 — неавтоматическое (общим выталкивателем), в ружье ТОЗ-34Е имеется эжекторный
			| механизм.
		p.card__description_text
			| Цевьё — неотъёмное, состоит из двух частей, закреплённых
			| на стволах винтами.
		p.card__description_text
			| Приклад с пистолетной формой шейки с выступом под щёку
			| выполнен из берёзовой, ореховой или буковой древесины.
	.card__characteristic.hidden
		.characteristic__brand.characteristic__box
			.characteristic__title Бренд:
			.characteristic__desc Тульский оружейный завод (ТОЗ)
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

	},
	methods: {

	},
	created(){

	}
}
</script>

<style scoped lang = "scss">
</style>