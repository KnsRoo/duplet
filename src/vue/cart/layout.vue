<template lang = "pug">
section.cart
	.wrapper
		p.cart__title Корзина
		.cart__order
			.cards
				.cards__titles
					.cards__titles_text(v-for="item in ['ПРОДУКТ','СКИДКА','Цена','Количество','Итого']") {{ item }}
				.cards__items
					cartItem(v-for="item in getItems" :cartItem="item")
				.attention(v-if = "reservedExists")
					.attention__block
						.attention__icon !
						.attention__title Внимание!
					.attention__text В вашей корзине есть товары, которые доступны только для резервирования. Вы можете зарезервировать эти товары и приобрести в наших магазинах, предъявив лицензию на покупку оружия
						a.attention__info(href='#') (подробнее об этом)
			.confirm
				.confirm__box
					.for__payment
						.order__title К оплате:
						.order__price ₽ {{ getCartStat.sumTotal }}
					.order__box
						.products
							.order__title
								.title__text Товары,
								.title__number
									.title__number_sum {{ getCartStat.countTotal }}
									.title__number_text шт.
							.order__price ₽ 20 109 110
						.discount
							.order__title скидка
							.order__price ₽ 20 109 110
						.payment__delivery
							.order__title доставка
							.order__price ₽ 20 109 110
					.in-all
						.order__title Итого:
						.order__price ₽ {{ getCartStat.sumTotal }}
				a.confirm__link(href='#') оформить заказ
			.delivery__choose
				.delivery__title Доставка
				.delivery__take
					input#post.custom__radio(type='radio' value='1')
					label.custom__label(for='post') Почтой России
					input#yourself.custom__radio(type='radio' value='2')
					label.custom__label(for='yourself') Самовывоз (г. Сыктывкар)
				.delivery__mail
					input.mail__input(v-model = "orderData.name" placeholder='ФИО')
					the-mask.mail__input(v-model = "orderData.phone" :mask="['+7 (###) ###-##-##']" placeholder='Телефон')
					input.mail__input(v-model = "orderData.city" placeholder='Город')
					input.mail__input(v-model = "orderData.address" placeholder='Адрес')
			.delivery__payment
				.delivery__payment_title Оплата
				.delivery__payment_take
					input#pay.custom__radio(type='radio' value='1')
					label.custom__label(for='pay') Картой онлайн
				.delivery__payment_cards
					.delivery__payment_cards_title Мы принимаем:
					.delivery__payment_cards_block
						img.payment-card(src='/assets/img/card-visa.png' alt='')
						img.payment-card(src='/assets/img/card-mastercard.png' alt='')
						img.payment-card(src='/assets/img/card-maestro.png' alt='')
						img.payment-card(src='/assets/img/card-mir.png' alt='')


</template>

<script>
import ky from "ky";
import { required } from "vuelidate/lib/validators";
import cartItem from "./cart-item.vue";
import { mapActions, mapGetters } from "vuex";

export default {
    data() {
        return {
            loaded: false,
            orderData: {
                name: null,
                phone: null,
                city: null,
                address: null
            }
        };
    },
    components: {
        cartItem
    },
    validations: {
        orderData: {
            name: {
                required
            },
            phone: {
                required
            },
            city: {
                required
            },
            address: {
                required
            }
        }
    },
    computed: {
        ...mapGetters("cart", ["getItems", "getCartStat"]),
        ...mapGetters("user", ["getUser"]),
        reservedExists() {
            let result = false;
            this.getItems.forEach(val => {
                if (val.status === "reserved") {
                    result = true;
                }
            });
            return result;
        }
    },
    methods: {
        ...mapActions("cart", ["fetchItems"]),
        ...mapActions("user", ["fetchUser"])
    },
    async created() {
        await this.fetchItems();
        await this.fetchUser();
        this.orderData.name = this.getUser.name;
        this.orderData.phone = this.getUser.phone;
        this.orderData.address = this.getUser.address;
    }
};
</script>