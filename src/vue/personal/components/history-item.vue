<template lang="pug">
.order__wrapper
	.order__info
		.title Заказчик: {{ customer }}
		.title Дата заказа: {{ date }}
		.title Адрес доставки: {{ orderProps['Адрес'].value }}
		.title Сумма: {{ summ }}
		.title Статус заказа: {{ orderProps['Статус'].value }}
	.mini__wrapper(v-if = "!detailed")
		.mini__box
			img.mini__image(v-for = "item in orderProps['Товары'].value.slice(0,6)" :src="item.picture")
			.title(v-if = "orderProps['Товары'].value.length > 6") ...
		.details
			.text(@click="detailed = !detailed") Развернуть
	.reserved__box(v-else)
		.name__box
			.subtitles__thing.name__title Товар
			.subtitles__cost.name__title Стоимость
			.subtitles__cost.name__title Количество
			.subtitles__cost.name__title Скидка
		.reserved__things
			ProductItem(v-for="item in orderProps['Товары'].value" :product = "item")
		.details
			.text(@click="detailed = !detailed") Скрыть

</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import ProductItem from './order-item.vue'

export default {
	data() {
		return {
			detailed: false
		};
	},
	computed: {
		customer() {
			return `${this.$props.orderProps['Фамилия'].value} ${this.$props.orderProps['Имя'].value} ${this.$props.orderProps['Отчество'].value}`
		},
		date(){
			let [date, time] = this.$props.orderProps['Дата'].value.split(' ')
			let [year, month, day] = date.split('-')
			let [hour, min, sec] = time.split(':')
			return `${day}.${month}.${year} ${hour}:${min}`
		},
		summ(){
			return parseFloat(this.$props.orderProps['Сумма'].value).toFixed(2)
		}
	},
	components: {
		ProductItem
	},
	props: {
		orderProps: Object
	},
	created(){
		console.log(this.$props.orderProps['Товары'])
	}
}
</script>

<style scoped lang = "scss">

.name__title {
    display: flex;
    justify-content: center;
}

.order {
	&__wrapper {
		display: flex;
		justify-content: space-between;
		border-bottom: 1px solid #e0e0e0;
		margin-top: 20px;
	}

	&__info {
		display: flex;
		flex-direction: column;
		grid-gap: 10px;
		margin-bottom: 20px;

		.title {
			font-weight: bold;
		}
	}
}

.details{
	display: flex;
	justify-content: center;
	margin-top: 10px;
	margin-bottom: 20px;

	.text {
		cursor: pointer;
		color: #888;
	}
}

.mini {
	&__box {
		display: flex;
		gap: 10px;

		.title {
			font-weight: bold;
			margin-top: 30px;
		}
	}

	&__image {
		width: 50px;
		height: 50px;
	}
}


</style>