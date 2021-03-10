<template lang = "pug">
.cart__now
	.cart__now_title Корзина сейчас
	.cart__wrap
		.cart__empty(v-if = "getItems.length == 0")
			.cart__empty__wrapper
				img(src = "/assets/img/icons/mdi-light_cart.svg")
				.cart__empty__notification Ваша корзина пока пуста
				.cart__empty__motivator Начните свои покупки прямо сейчас
				a.btn.btn__to(href = "/catalog")
					span.btn__name в каталог
		.cart__booked(v-if="reservedItems.length != 0")
			.cart__booked_title Бронирование
			cartItem(v-for="item in reservedItems" :item = "item")
		.cart__bought(v-if="readyItems.length != 0")
			.cart__bought_title Покупка
			cartItem(v-for="item in readyItems" :item = "item")
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import cartItem from "./cart-item.vue";

export default {
    data() {
        return {};
    },
    components: {
        cartItem
    },
    computed: {
        ...mapGetters("cart", ["reservedItems", "readyItems", "getItems"])
    },
    methods: {
        ...mapActions("cart", ["fetchItems"])
    },
    async created() {
        await this.fetchItems();
        this.$emit("toggleLoad", { component: "cart", value: true });
    }
};
</script>

<style scoped lang = "scss">
.cart {
    &__wrap{
        margin-top: 80px;
    }

    &__now{
        &_title{
            display: flex;
            justify-content: center;
        }
    }

    &__empty{
        &__wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        &__notification {
            font-size: 24px;
            margin-top: 40px;
        }

        &__motivator {
            font-size: 18px;
            color: #888;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        .btn__to {
            width: 230px;
            height: 70px;
            font-size: 18px;
            line-height: 20px;
        }
    }
}
</style>
