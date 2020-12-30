<template lang="pug">
.catalog
    .wrapper
        Product(
            v-for="product in items"
            :key="product.id"
            :product="product"
            )
    
    //- MobileItems
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';
import Product from "../components/catalog-item.vue";
import Filter from "../components/filter.vue";
import Pager from "../components/pager.vue";
import MobileItems from "../components/mobile-items.vue";

export default {
    data() {
        return {
            resizeConut: "",
            isvisible: true,
            catalogBaseView: true,
            pagesNumber: [],
            items: [],
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
        offset() {
            this.fetchItems();
        },
        resizeConut() {
            this.resizeConut < 576 ? (this.catalogBaseView = true) : "";
        }
    },
    methods: {
        ...mapGetters("catalog",["getFilterGroupId","getGroupId"]),
        ...mapMutations("catalog", ["setFilterGroupId", "setGroupId"]),
        handleResize() {
            this.resizeConut = document.body.clientWidth;
        },

        fetchItems() {
            this.items = [];
            fetch(
                `${api.catalog}?offset=${this.offset * this.limit}&limit=${
                    this.limit
                }`
            )
                .then(response => {
                    return response.json();
                })
                .then(result => {
                    const products = result._embedded.items;
                    const pageNumber = Math.floor(result.total / result.limit);
                    this.pagesNumber = pageNumber;

                    products.forEach(element => {
                        element.count = 1;
                        this.items.push(element);
                    });
                });
        },
        fetchProducts() {
            const id = this.getGroupId();

            const origin = document.location.origin;
            const link = `${origin}/api/catalog/products`;
            this.items = [];
            fetch(link)
                .then(response => response.json())
                .then(result => {
                    const products = result._embedded.items;
                    const pageNumber = Math.floor(result.total / result.limit);
                    this.pagesNumber = pageNumber;
                    // this.pagesNumber = pageNumber;

                    // console.log(result);

                    products.forEach(element => {
                        element.count = 1;
                        this.items.push(element);
                    });
                    // let items = result._embedded.items;
                });
        },
        loadProducts()
        {
            if (localStorage.getItem("filterId")) {
                this.fetchProducts();
            } else {
                if (localStorage.getItem("items")) {
                    this.items = [];
                    const response = localStorage.getItem("items");
                    const result = JSON.parse(response);
                    const products = result._embedded.items;
                    const pageNumber = Math.floor(result.total / result.limit);
                    this.pagesNumber = pageNumber;

                    products.forEach(element => {
                        element.count = 1;
                        this.items.push(element);
                    });
                    localStorage.removeItem("items");
                } else {
                    this.fetchItems();
                }
        }

        window.addEventListener("resize", this.handleResize);
        }
    },

    created() {
        this.setFilterGroupId(localStorage.getItem("filterId"));
        this.setGroupId(localStorage.getItem("filterId"));
        this.loadProducts();
    },
    computed:{

    },
    destroyed() {
        window.removeEventListener("resize", this.handleResize);
    }
};
</script>
