<template lang="pug">
section.catalog
    .wrapper
        catalog-cats(:mode = "mode" :query = "query" @switchToCatalog="switchCat")
        .cards
            Product(
                v-for="product in catalogItems"
                :key="product.id"
                :product="product"
                )
        .catalog__next(v-if="isNext")
            a.link(@click="nextItems()") Загрузить еще
    
    //- MobileItems
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';
import Product from "../components/catalog-item.vue";
import Cats from "../components/categories.vue";
import Pager from "../components/pager.vue";
import MobileItems from "../components/mobile-items.vue";

export default {
    data() {
        return {
            resizeConut: "",
            isvisible: true,
            catalogBaseView: true,
            pagesNumber: [],
            offset: 0,
            limit: 30,
            filterGroupId: '',
            query: null
        };
    },
    components: {
        Product,
        "catalog-cats": Cats,
        "catalog-pager": Pager,
        MobileItems
    },
    watch: {
    },
    computed: {
        ...mapGetters("catalog",["catalogItems", "isNext"]),
    },
    methods: {
        ...mapActions("catalog", ["fetchCatalogItems", "nextItems"]),
        handleResize() {
            this.resizeConut = document.body.clientWidth;
        },
        loadProducts(){
            window.addEventListener("resize", this.handleResize);
        },
    },

    async created() {
        let query = new URL(document.location).searchParams.get("query")
        this.mode = 'catalog'
        if (query){
            await this.fetchCatalogItems(`${window.location.origin}/api/catalog/products?query=${query}`)
            this.mode = 'search'
            this.query = query
        } else {
            await this.fetchCatalogItems(`${window.location.origin}/api/catalog/products`)
        }
    },
    destroyed() {
        window.removeEventListener("resize", this.handleResize);
    }
};
</script>
