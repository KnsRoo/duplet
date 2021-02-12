<template lang = "pug">
section.cart
	.wrapper
		p.title__page Корзина
		loader(v-if = "!loaded")
		.cart__order(v-if = "getItems.length != 0 && loaded")
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
						.order__price ₽ {{ getCartStat.sumTotal.toFixed(2) }}
					.order__box
						.products
							.order__title
								.title__text Товары,
								.title__number
									.title__number_sum {{ getCartStat.countTotal }}
									.title__number_text шт.
							.order__price ₽ {{ getCartStat.sumReal.toFixed(2) }}
						.discount
							.order__title скидка
							.order__price ₽ {{ getCartStat.discount.toFixed(2) }}
						.payment__delivery
							.order__title доставка
							.order__price ₽ ???
					.in-all
						.order__title Итого:
						.order__price ₽ {{ getCartStat.sumTotal.toFixed(2) }}
				a.confirm__link(href='#') оформить заказ
			.delivery__choose
				.delivery__title Доставка
				.delivery__take
					input#post.custom__radio(type="radio" value="post" name="delivery" checked)
					label.custom__label.label__post(for="post")
						span.custom__label_icon
						span.custom__label_title Почтой россии
					input#yourself.custom__radio(type="radio" value="yourself" name="delivery")
					label.custom__label.label__yourself(for="yourself")
						span.custom__label_icon
						span.custom__label_title Самовывоз (в г. Сыктывкар)
				.delivery__mail
					input.mail__input(v-model = "orderData.name" placeholder='ФИО')
					the-mask.mail__input(v-model = "orderData.phone" :mask="['+7 (###) ###-##-##']" placeholder='Телефон')
					input.mail__input(v-model = "orderData.city" placeholder='Город')
					input.mail__input(v-model = "orderData.address" placeholder='Адрес')
			.delivery__payment
				.delivery__payment_title Оплата
				.delivery__payment_take
					input#pay.custom__radio(type="radio" value="1" checked)
					label.custom__label.label__pay(for="pay")
						span.custom__label_icon
						span.custom__label_title Картой онлайн
				.delivery__payment_cards
					.delivery__payment_cards_title Мы принимаем:
					.delivery__payment_cards_block
						img.payment-card(src='/assets/img/card-visa.png' alt='')
						img.payment-card(src='/assets/img/card-mastercard.png' alt='')
						img.payment-card(src='/assets/img/card-maestro.png' alt='')
						img.payment-card(src='/assets/img/card-mir.png' alt='')
		.cart__empty(v-if = "getItems.length == 0 && loaded")
			.cart__empty__wrapper
				img(src = "/assets/img/icons/mdi-light_cart.svg")
				.cart__empty__notification Ваша корзина пока пуста
				.cart__empty__motivator Начните свои покупки прямо сейчас
				a.btn.btn__to(href = "/catalog")
					span.btn__name в каталог


</template>

<script>
import ky from "ky";
import { required } from "vuelidate/lib/validators";
import cartItem from "./cart-item.vue";
import loader from "../loader/index.vue";
import { mapActions, mapGetters } from "vuex";

export default {
    data() {
        return {
            loaded: false,
            orderData: {
                name: null,
                phone: null,
                city: null,
                address: null,
                loaded: false
            }
        };
    },
    components: {
        cartItem,
        loader
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
        if (localStorage.getItem("jwt")){
        	await this.fetchUser();
	        this.orderData.name = this.getUser.name;
	        this.orderData.phone = this.getUser.phone;
	        this.orderData.address = this.getUser.address;
    	}
    	this.loaded = true
    }
};
</script>
