<template lang = "pug">
.lk__wrapper
    section.lk
        .wrapper
            .lk__title Личный кабинет
            loader(v-if = "!loaded")
            .lk__block(v-show = "loaded")
                Info(@toggleLoad="toggleLoad")
                Cart(@toggleLoad = "toggleLoad")
    Reserved(v-show = "loaded" @toggleLoad = "toggleLoad")
    History(v-show = "loaded" @toggleLoad = "toggleLoad")


</template>

<script>
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
                'reserved': false,
                'history': false
            }
        };
    },
    methods: {
        toggleLoad(object){
            this.awaits[object.component] = object.value
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