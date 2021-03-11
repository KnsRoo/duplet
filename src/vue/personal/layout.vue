<template lang = "pug">
.lk__wrapper
    section.lk
        .wrapper
            .title__page Личный кабинет
            loader(v-if = "!loaded")
            .lk__block(v-show = "loaded")
                Info(@toggleLoad="toggleLoad")
                Cart(@toggleLoad = "toggleLoad")
    .defs
        .catalog__cat__item(v-show = "loaded" v-for="item in defs" @click = "setActive(item)" :class = "{active: item == active}")
            .catalog__cat__item_title {{ item }}
    Reserved(v-if = "loaded && active == 'Забронированные товары'" @toggleLoad = "toggleLoad")
    History(v-if = "loaded && active == 'История покупок'" @toggleLoad = "toggleLoad")


</template>

<script>
import { mapActions } from 'vuex'
import ky from "ky";
import Info from "./components/info.vue";
import Cart from "./components/cart.vue";
import Reserved from "./components/reserved.vue";
import History from "./components/history.vue";
import loader from "../loader/index.vue";

export default {
    data() {
        return {
            awaits: {
                'info': false,
                'cart': false,
                'reserved': true,
                'history': true
            },
            defs: ['История покупок', 'Забронированные товары'],
            active: 'История покупок'
        };
    },
    methods: {
        ...mapActions("orders", ["fetchOrders", "fetchNextOrders"]),
        toggleLoad(object){
            this.awaits[object.component] = object.value
        },
        async setActive(item){
            // let target = (this.active == "История покупок") ? 'Покупка' : 'Бронирование'
            // await this.fetchOrders(target)
            this.active = item
        }
    },
    computed: {
        loaded(){
            if ((Object.values(this.awaits).filter(val => val == false)).length){
                return false
            } else {
                return true
            }
        }
    },
    components: {
        Info,
        Cart,
        Reserved,
        History,
        loader
    },
    async mounted() {},
    async created() {
    }
};
</script>

<style scoped lang="scss">
.defs{
    display: flex;
    justify-content: center;
    gap: 20px;

    .catalog__cat__item {
        width: 185px;
        height: 70px;
        border: 1px solid #888;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;

        &:hover {
            background: #333;
            color: white;
            cursor: pointer;
        }

        &_title {
            font-family: FuturaBookC;
            font-weight: 400;
            font-size: 18px;
            line-height: 22.5px;
        }
    }
}

.active {
    background: #333;
    color: white;
    cursor: pointer;
}
</style>