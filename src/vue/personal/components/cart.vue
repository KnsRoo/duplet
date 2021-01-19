<template lang = "pug">
.cart
	.cart__now Корзина сейчас
	.cart__block
		.cart__booked
			.cart__booked_title Бронирование
			.cart__booked_box
				.card
					.card__act
						.card__bookmark
							img.card__icon(src="/assets/img/bookmark.svg" alt="")
							.card__title Отложить
						.card__delete
							img.card__icon(src="/assets/img/delete.svg" alt="")
							.card__title Удалить
					.card__des
						.card__img
							img(src="/assets/img/big-gun.png" alt="")
						.card__text
							.card__text_title
								.card__text_title_subtitle ТОЗ-34Р (орех)
							.card__text_block
								.card__brand
									.card__brand_title Бренд:
									.card__brand_name ТОЗ
								.card__caliber
									.card__caliber_title Калибр:
									.card__caliber_name 12/70
					.card__number 100
					.card__in-all
						.card__in-all_price ₽1 739 000
						.card__in-all_discount ₽1 739 00
		.cart__bought
			.cart__bought_title Покупка
			.cart__bought_box
</template>

<script>
export default {
	data() {
		return {

		};
	}
}
</script>
