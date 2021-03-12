<template lang="pug">
section.reserved
    .wrapper
        .reserved__box
            .name__box
                .subtitles__thing.name__title Товар
                .subtitles__address.name__title адрес получения
                .subtitles__info.name__title Подробности получения
                .subtitles__cost.name__title Стоимость
                .subtitles__status.name__title Статус заказа
            .reserved__things(v-if = "loaded")
                loader(v-if = "converting")
                ProductItem(v-for="item in orderItems" :order = "item")
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
import ProductItem from './reserved-item.vue'
import loader from "../../loader/index.vue";

export default {
    data() {
        return {
            loaded: false,
            converting: true
        };
    },
    components: {
        loader,
        ProductItem
    },
    computed: {
        ...mapGetters("orders", ["orders"]),
        orderItems(){
            let orders = []
            this.orders.items.forEach(val => {
                console.log("val", val)
                console.log("props", val.props)
                val.props['Товары'].value.forEach(v => {
                    orders.push({
                        "Статус": val.props['Статус'].value,
                        "Товар": v
                    })
                })
            })
            this.converting = false
            return orders
        }
    },
    methods: {
        ...mapActions("orders", ["fetchOrders", "fetchNextOrders"])
    },
    async created(){
        await this.fetchOrders('Бронирование')
        console.log(this.orders)
        this.loaded = true
        this.$emit('toggleLoad', { component: 'reserved', value: true})
    }
};
</script>

<style scoped lang = "scss">
.reserved__box {
    margin-top: 20px;
}
</style>
