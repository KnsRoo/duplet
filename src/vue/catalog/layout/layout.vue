<template lang="pug">
section.catalog
    .wrapper
        catalog-cats(ref = "cats" :mode = "mode" :query = "query" @switchToCatalog="switchCat")
        .cards
            Product(
                v-for="product in catalogItems"
                :key="product.id"
                :product="product"
                )
        .catalog__next(v-if="isNext")
            .link__to.show__more(@click="nextItems()") Загрузить еще
    //- article.better__category
    //-     .wrapper
    //-         .image__slider.swiper-container
    //-             .image__slider_wrapper.swiper-wrapper
    //-                 .swiper-slide
    //-                     a.card.discount(href="#")
    //-                         .discount__percent 15%
    //-                         .favorite__cross.icon-menu-cancel
    //-                         .card__block
    //-                             img.card__img(src=("/assets/img/gun.png"), alt="Ружьё")
    //-                         .card__content
    //-                             .card__content_title
    //-                                 .card__content_title_name КОСТЮМ АНТИГНУС ДЕТСКИЙ ТК. СМЕСОВАЯ ЦВ. ЛЕС (РАЗМЕР 36-38/134-140
    //-                                 .card__content_title_price
    //-                                     .first__price ₽ 77 900
    //-                                     .discount__price ₽ 34 999
    //-                             figure.icon-add
    //-                 .swiper-slide
    //-                     a.card.discount(href="#")
    //-                         .discount__percent 15%
    //-                         .favorite__cross.icon-menu-cancel
    //-                         .card__block
    //-                         .card__content
    //-                             .card__content_title
    //-                                 .card__content_title_name КОСТЮМ АНТИГНУС ДЕТСКИЙ ТК. СМЕСОВАЯ ЦВ. ЛЕС (РАЗМЕР 36-38/134-140
    //-                                 .card__content_title_price
    //-                                     .first__price ₽ 77 900
    //-                                     .discount__price ₽ 34 999
    //-                             figure.icon-add
    //-                 .swiper-slide
    //-                     a.card.discount(href="#")
    //-                         .discount__percent 15%
    //-                         .favorite__cross.icon-menu-cancel
    //-                         .card__block
    //-                             img.card__img(src=("/assets/img/termos.jpg"), alt="Ружьё")
    //-                         .card__content
    //-                             .card__content_title
    //-                                 .card__content_title_name КОСТЮМ АНТИГНУС ДЕТСКИЙ ТК. СМЕСОВАЯ ЦВ. ЛЕС (РАЗМЕР 36-38/134-140
    //-                                 .card__content_title_price
    //-                                     .first__price ₽ 77 900
    //-                                     .discount__price ₽ 34 999
    //-                             figure.icon-add
    //-                 .swiper-slide
    //-                     a.card.discount(href="#")
    //-                         .discount__percent 15%
    //-                         .favorite__cross.icon-menu-cancel
    //-                         .card__block
    //-                             img.card__img(src=("/assets/img/termos.jpg"), alt="Ружьё")
    //-                         .card__content
    //-                             .card__content_title
    //-                                 .card__content_title_name КОСТЮМ АНТИГНУС ДЕТСКИЙ ТК. СМЕСОВАЯ ЦВ. ЛЕС (РАЗМЕР 36-38/134-140
    //-                                 .card__content_title_price
    //-                                     .first__price ₽ 77 900
    //-                                     .discount__price ₽ 34 999
    //-                             figure.icon-add
    //-             .swiper-pagination
    //- MobileItems
</template>

<script>
import ky from "ky";
import { mapGetters, mapActions, mapMutations } from "vuex";
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
            filterGroupId: "",
            query: null
        };
    },
    components: {
        Product,
        "catalog-cats": Cats,
        "catalog-pager": Pager,
        MobileItems
    },
    watch: {},
    computed: {
        ...mapGetters("catalog", ["catalogItems", "isNext"])
    },
    methods: {
        ...mapActions("catalog", ["fetchCatalogItems", "nextItems"]),
        handleResize() {
            this.resizeConut = document.body.clientWidth;
        },
        loadProducts() {
            window.addEventListener("resize", this.handleResize);
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
    },
    destroyed() {
        window.removeEventListener("resize", this.handleResize);
    }
};
</script>
