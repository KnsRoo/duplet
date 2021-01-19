<template lang = "pug">
.lk__wrapper
    section.private-office
        .wrapper
            .private-office__title Личный кабинет
            .private-office__flex
                Info
                Cart
    Reserved
    History

</template>

<script>
import ky from "ky";
import Info from "./components/info.vue";
import Cart from "./components/cart.vue";
import Reserved from "./components/reserved.vue";
import History from "./components/history.vue";

export default {
    data() {
        return {
        };
    },
    components: {
        Info,
        Cart,
        Reserved,
        History
    },
    computed: {
    },
    methods: {
    },
    async mounted(){

    },
    async created() {
    }
};
</script>