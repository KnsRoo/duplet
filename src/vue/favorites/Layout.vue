<template lang = "pug">
section.liked
	.wrapper
		p.title__page Отложенные товары
		.liked__wrap(v-if = "loaded")
			FavItem(v-for="item in favorites" :product = "item" :favorite = "true")
</template>

<script>
import ky from "ky";
import FavItem from "../catalog/components/catalog-item.vue";
import { mapActions, mapGetters } from "vuex";

export default {
    data() {
        return {
            loaded: false
        };
    },
    components: {
        FavItem
    },
    computed: {
        ...mapGetters("favorites", ["favorites"])
    },
    methods: {
        ...mapActions("favorites", ["fetchItems"])
    },
    async created() {
        await this.fetchItems();
        this.loaded = true;
    }
};
</script>