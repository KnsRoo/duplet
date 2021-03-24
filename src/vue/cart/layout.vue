<template lang = "pug">
section.cart
	Popup(v-if="show" :type = "type" @close = "closePopup")
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
					.attention__text В вашей корзине есть товары, которые доступны только для резервирования. Вы можете зарезервировать эти товары и приобрести в наших магазинах при личном визите
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
						.payment__delivery(v-show = "delivery == 'Доставка'")
							.order__title доставка
							.order__price ₽ ???
					.in-all
						.order__title Итого:
						.order__price ₽ {{ getCartStat.sumTotal.toFixed(2) }}
				a.confirm__link(@click = "sendOrder") оформить заказ
			.delivery__choose
				.delivery__title Доставка
				.delivery__take
					input#post.custom__radio(v-model = "delivery" type="radio" value="Доставка")
					label.custom__label.label__post(for="post")
						span.custom__label_icon
						span.custom__label_title Почтой россии
					input#yourself.custom__radio(v-model = "delivery" type="radio" value="Самовывоз")
					label.custom__label.label__yourself(for="yourself")
						span.custom__label_icon
						span.custom__label_title Самовывоз (в г. Сыктывкар)
				.delivery__mail
					.delivery__take(v-if = "delivery == 'Самовывоз'")
						input#shop1.custom__radio( v-model = "shopAddress" type="radio" value="Южная, 6")
						label.custom__label.label__post(for="shop1")
							span.custom__label_icon
							span.custom__label_title Южная, 6
						input#shop2.custom__radio(v-model = "shopAddress" type="radio" value="Гаражная, 27")
						label.custom__label.label__yourself(for="shop2")
							span.custom__label_icon
							span.custom__label_title Гаражная, 27
					.input(:class = "{ mistake: !nameValid }")
						input.for__input(v-model = "orderData.name" type="text" placeholder="ФИО")
						.mistake__info
							figure.icon-info
							.mistake__info_title Ой, кажется такого имени не существует
					.input(:class = "{ mistake: !emailValid }")
						input.for__input(v-model = "orderData.email" type="text" placeholder="E-mail")
						.mistake__info
							figure.icon-info
							.mistake__info_title Ой, кажется такого e-mail не существует
					.input(:class = "{ mistake: !phoneValid }")
						the-mask.for__input(v-model = "orderData.phone" :mask="['+7 (###) ###-##-##']" type="text" placeholder="Телефон")
						.mistake__info
							figure.icon-info
							.mistake__info_title Ой, кажется такого телефона не существует
					.input(v-if = "delivery == 'Доставка'" :class = "{ mistake: !cityValid }")
						input.for__input(v-model = "orderData.city" type="text" placeholder="Город")
						.mistake__info
							figure.icon-info
							.mistake__info_title Ой, кажется такого города не существует
					.input(v-if = "delivery == 'Доставка'" :class = "{ mistake: !addressValid }")
						input.for__input(v-model = "orderData.address" type="text" placeholder="Адрес") 
						.mistake__info
							figure.icon-info
							.mistake__info_title Ой, кажется такого адреса не существует
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
import { required, email } from "vuelidate/lib/validators";
import cartItem from "./cart-item.vue";
import loader from "../loaders/ellipse.vue";
import { mapActions, mapGetters } from "vuex";
import refreshToken from '../../js/components/refreshToken'
import noty from '../../js/components/noty'
import Popup from './popup.vue'

export default {
	data() {
		return {
			loaded: false,
			show: false,
			validationsActive: false,
			type: null,
			delivery: 'Доставка',
			shopAddress: 'Южная, 6',
			orderData: {
				name: null,
				phone: null,
				city: null,
				address: null,
				email: null,
				paytype: 'Онлайн'
			}
		};
	},
	components: {
		cartItem,
		Popup,
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
			email: {
				required,
				email
			},
			city: {
				required
			},
			address: {
				required
			},
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
		},
		nameValid(){
			if (!this.validationsActive) return true
			return this.$v.orderData.name.required
		},
		phoneValid(){
			if (!this.validationsActive) return true
			return this.$v.orderData.phone.required && this.orderData.phone.length == 10
		},
		emailValid(){
			if (!this.validationsActive) return true
			return this.$v.orderData.email.required && this.$v.orderData.email.email
		},
		cityValid(){
			if (!this.validationsActive) return true
			return this.$v.orderData.city.required
		},
		addressValid(){
			if (!this.validationsActive) return true
			return this.$v.orderData.address.required
		},
	},
	methods: {
		...mapActions("cart", ["fetchItems", "addOrder"]),
		...mapActions("user", ["fetchUser"]),
		async isAuth(){
			try {
				let response = await refreshToken();
				return true
			} catch (err){
				return false
			}
		},
		showPopup(type){
			this.type = type
			this.show = true
		},
		closePopup(){
			this.show = false
			this.type = null
		},
		async sendOrder(){
			let auth = await this.isAuth()
			if (!auth){
				window.modalLogin.toggle()
				return
			}
			this.validationsActive = true
			let result = false;
			if (this.nameValid && 
				this.phoneValid &&
				this.emailValid){
				let body = {
					'ФИО': this.orderData.name,
					'Телефоны': ['7'+this.orderData.phone],
					'Электронные почты': [this.orderData.email],
					'Способ получения': this.delivery,
					'Способ оплаты': this.orderData.paytype,
				}
				if (this.delivery == 'Доставка'){
					if (this.cityValid && this.addressValid){
						body['Адрес'] = `${this.orderData.city} ${this.orderData.address}`,
						result = this.addOrder(body)
					} else {
						noty('error', 'Проверьте правильность заполнения полей')
					}
				} else {
					body['Магазин'] = this.shopAddress
					result = this.addOrder(body)
				}
			} else {
				noty('error', 'Проверьте правильность заполнения полей')
			}
			if (result){
				this.showPopup('success')
			} else {
				this.showPopup('error')
			}
		}
	},
	async created() {
		await this.fetchItems();
		if (localStorage.getItem("jwt")) {
			await this.fetchUser();
			this.orderData.name = this.getUser.name;
			this.orderData.phone = this.getUser.phone;
			this.orderData.address = this.getUser.address;
		}
		this.loaded = true;
	}
};
</script>

