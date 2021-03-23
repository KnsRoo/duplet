<template lang = "pug">
.card
	.card__des.card__info
		.card__wrap
			.card__img
				.product
					.discount__percent {{ order['Товар'].discount }}
					.favorite__cross.icon-menu-cancel
					.product__block
						img.product__img(v-if = "order['Товар'].picture" :src="order['Товар'].picture", alt="")
						img.product__img(v-else src="/assets/img/default.png", alt="")
			.card__text
				.card__text_title {{ order['Товар'].title }}
				.card__text_block
					.card__brand
						.card__brand_title Бренд:
						.card__brand_name ТОЗ
					.card__caliber
						.card__caliber_title Калибр:
						.card__caliber_name 12/70
				.status__mobile
					.status__mobile_title Статус:
					.status__mobile_wrap
						.status__done {{ order['Статус'] }}
						.status__building.hidden {{ order['Статус'] }}
		.card__information
			figure.icon-info
			.card__information__text Информация
	.card__address.card__info
		.street ул. Южная 6 (40-00-70)
		.work__time
			.work__time_title Режим работы:
			.date
				.week Пн-Пт
				.time 10:00 - 19:00
			.date
				.week Сб, Вс
				.time 10:00 - 17:00
	.card__reception.card__info
		.get__thing  Товар будет доступен для получения с
			span.get__thing_date {{ order['Доступен с'] }}
		.booked__thing  Бронь закрепляется за Вами до
			span.booked__thing_date {{ order['Бронь до'] }}
	.card__cost.card__info ₽ {{ order['Товар'].price.toFixed(2) }}
	.card__status.card__info
		.status__done.hidden__status  {{ order['Статус'] }}
		.status__building {{ order['Статус'] }}
</template>
<script>
export default {
	data(){
		return {
		}
	},
	props: {
		order: Object
	},
	created(){
		console.log(this.$props.product)
	}
}
</script>