<template lang="pug">
section.catalog
    .wrapper
        catalog-cats(@toggleLoad = "toggleLoad" ref = "cats" :mode = "mode" :query = "query" @switchToCatalog="switchCat")
        loader(v-if = "!loaded")
        .cards(v-else)
            Product(
                v-for="product in catalogItems"
                :key="product.id"
                :product="product"
                )
        loader(v-if="!fetched")
        .catalog__next(v-if="isNext && loaded && fetched")
            .link__to(@click="next") Загрузить еще
</template>

<script>
import ky from "ky";
import { mapGetters, mapActions, mapMutations } from "vuex";
import Product from "./components/catalog-item.vue";
import Cats from "./components/categories.vue";
import Pager from "./components/pager.vue";
import MobileItems from "./components/mobile-items.vue";
import loader from "../loader/index.vue";

export default {
    data() {
        return {
            resizeConut: "",
            isvisible: true,
            catalogBaseView: true,
            pagesNumber: [],
            offset: 0,
            limit: 30,
            filterGroupId: "",
            query: null,
            loaded: false,
            fetched: true
        };
    },
    components: {
        Product,
        "catalog-cats": Cats,
        "catalog-pager": Pager,
        MobileItems,
        loader
    },
    watch: {},
    computed: {
        ...mapGetters("catalog", ["catalogItems", "isNext"])
    },
    methods: {
        ...mapActions("catalog", ["fetchCatalogItems", "nextItems"]),
        async next() {
            this.fetched = false;
            await this.nextItems();
            this.fetched = true;
        },
        handleResize() {
            this.resizeConut = document.body.clientWidth;
        },
        loadProducts() {
            window.addEventListener("resize", this.handleResize);
        },
        toggleLoad(value) {
            this.loaded = value;
        }
    },

    async created() {
        let query = new URL(window.location.href).searchParams.get("query"),
            groupId = new URL(window.location.href).searchParams.get("group"),
            sort = new URL(window.location.href).searchParams.get("sort");

        this.mode = "catalog";

        if (query) {
            await this.fetchCatalogItems(
                `${window.location.origin}/api/catalog/products?query=${query}`
            );
            this.mode = "search";
            this.query = query;
        } else {
            if (groupId) {
                let group = await ky
                    .get(
                        `${window.location.origin}/api/catalog/groups/${groupId}`
                    )
                    .json();
                await this.$refs.cats.setGroup(group);
                this.$refs.cats.toggleCats();
            } else {
                await this.fetchCatalogItems(
                    `${window.location.origin}/api/catalog/products`
                );
            }
            if (sort) {
                await this.$refs.cats.refreshSort(parseInt(sort));
            }
        }

        this.loaded = true;
    },
    destroyed() {
        window.removeEventListener("resize", this.handleResize);
    }
};
</script>
