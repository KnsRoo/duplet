<template lang = "pug">
.cart__now
	.cart__now_title Корзина сейчас
	.cart__wrap
		.cart__booked
			.cart__booked_title Бронирование
			.cart__booked_box
				.card
					.card__act
						.card__favorite
							figure.card__icon.icon-liked
							.card__title Отложить
						.card__delete
							figure.card__icon.icon-delete
							.card__title Удалить
					.card__des.card__info
						.card__img
							.product
								.discount__percent 15%
								.favorite__cross.icon-menu-cancel
								.product__block
									img.product__img(src=("/assets/img/gun.png"), alt="Ружьё")
						.card__text
							.card__text_title САПОГИ РЫБАЦКИЕ НАЗИЯ ЭВА С ЧУЛ. ТРИПЛЕКС АРТ. С095 (РАЗМЕР 46)
							.mobile__block_hidden
								.card__number_mobile
									.btn__minus.icon-number
									.choose__number 1
									.btn__plus.icon-number
								.card__in-all__mobile ₽99 739 000
							.card__text_block
								.card__brand
									.card__brand_title Бренд:
									.card__brand_name ТОЗ
								.card__caliber
									.card__caliber_title Калибр:
									.card__caliber_name 12/70
					.card__number.card__info
						.btn__minus.icon-number
						.choose__number 1
						.btn__plus.icon-number
					.card__in-all.card__info ₽99 739 000
		.cart__bought
			.cart__bought_title Покупка
			.cart__bought_box
</template>

<script>
export default {
    data() {
        return {};
    }
};
</script>
