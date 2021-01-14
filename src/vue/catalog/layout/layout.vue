<template lang="pug">
section.catalog
    .wrapper
        catalog-filter
        .cards
            Product(
                v-for="product in catalogItems"
                :key="product.id"
                :product="product"
                )
    
    //- MobileItems
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';
import Product from "../components/catalog-item.vue";
import Filter from "../components/categories.vue";
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
        };
    },
    components: {
        Product,
        "catalog-filter": Filter,
        "catalog-pager": Pager,
        MobileItems
    },
    watch: {
        // offset() {
        //     this.fetchItems();
        // },
        // resizeConut() {
        //     this.resizeConut < 576 ? (this.catalogBaseView = true) : "";
        // }
    },
    computed: {
        ...mapGetters("catalog",["catalogItems"]),
    },
    methods: {
        ...mapActions("catalog", ["fetchCatalogItems"]),
        handleResize() {
            this.resizeConut = document.body.clientWidth;
        },
        loadProducts(){
            window.addEventListener("resize", this.handleResize);
        }
    },

    async created() {
        await this.fetchCatalogItems(`${window.location.origin}/api/catalog/products`)
    },
    destroyed() {
        window.removeEventListener("resize", this.handleResize);
    }
};
</script>
