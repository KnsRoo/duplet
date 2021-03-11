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
                ProductItem(v-for="item in orders.items" :product = "item.props")
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
import ProductItem from './reserved-item.vue'

export default {
    data() {
        return {
            loaded: false
        };
    },
    components: {
        ProductItem
    },
    computed: {
        ...mapGetters("orders", ["orders"]),
        orderItems(){
            let orders = []
            this.orders.items.forEach(val => {
                let temp = {
                    
                }
            })
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
