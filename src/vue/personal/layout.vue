<template lang = "pug">
.lk__wrapper
    section.lk
        .wrapper
            .title__page Личный кабинет
            .lk__block
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
        return {};
    },
    components: {
        Info,
        Cart,
        Reserved,
        History
    },
    computed: {},
    methods: {},
    async mounted() {},
    async created() {}
};
</script>