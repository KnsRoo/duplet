<template lang="pug">
.filter_wrapper
    .catalog__title {{ title }}
    .catalog__cat(v-if = "mode == 'catalog'")
        .catalog__cat__show(@click = "toggleCats()")
            .menu__btn(v-bind:class='{menu__btn_active: show }')
                span
                span
                span
            .catalog__cat__title {{ (show) ? 'Скрыть категории' : 'Показать категории' }}
        .breadcrumbs__wrapper(v-if = "breadCrumbs")
            .breadcrumbs(v-for="(item, index) in breadCrumbs")
                a.link(v-if="index == 0" @click = "setDefault()") Все категории
                span.separator >
                a.link(v-if = "index != breadCrumbs.length-1" @click = "back(index,item)") {{ item.title }}
                a.link__last(v-else) {{ item.title }}
        .catalog__sort
            .catalog__sort_title Сортировка по:
            .catalog__sort_type
                .sort__name(@click = "toggleSort('name')")
                    .sort__name_title(:class = "{sort_active: [1,2].includes(sortType)}")  Названию
                    img(v-if = "[0,3,4].includes(sortType)" src = "/assets/img/icons/sort_neitral.svg")
                    img(v-else-if = "sortType == 1" src="/assets/img/icons/sort_down.svg")
                    img(v-else-if = "sortType == 2" src = "/assets/img/icons/sort_up.svg")
                .sort__price(@click = "toggleSort('price')")
                    .sort__price_title(:class = "{sort_active: [3,4].includes(sortType)}")  Цене
                    img(v-if = "[0,1,2].includes(sortType)" src = "/assets/img/icons/sort_neitral.svg")
                    img(v-else-if = "sortType == 3" src="/assets/img/icons/sort_down.svg")
                    img(v-else-if = "sortType == 4" src = "/assets/img/icons/sort_up.svg")
    .catalog__cat(v-else)
        .catalog__cat__show(@click = "switchToCatalog")
            img.cat_button( src="/assets/img/icons/menu-cancel.svg")
            .catalog__cat__title В каталог
    .catalog__cat__list(v-if="show")
        .catalog__cat__item(v-for="item in catalogGroups" @click = "setGroup(item)" :class = "{active: item.title == active}")
            .catalog__cat__item_title {{ item.title }}

</template>

<script>
import URLHistory from '../../../js/components/URLHistory'
import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
    props: {
        mode: String,
        query: String
    },
    data() {
        return {
            show: false,
            sortType: 0,
            breadCrumbs: [],
            active: ""
        };
    },

    methods: {
        ...mapActions("catalog", ["fetchCatalogGroups", "fetchCatalogItems", "fetchSortedCatalogItems", "sortItems"]),
        ...mapMutations("catalog", ["setFilterGroupId", "setGroupId"]),

        toggleCats() {
            this.show = !this.show;
        },

        /*
            sort Types:
            -----------
            0 | not soted
            1 | title A-Z
            2 | title Z-A
            3 | price 0-∞
            4 | price ∞-0
        */

        async refreshSort(type){
            try{
                if (![0,1,2,3,4].includes(type)){
                    throw new Error(`Unsupported sort type (${type})`);
                }
                this.sortType = type
                await this.sortItems(this.sortType);
            } catch (e) {
                console.error(e.message)
            }
        },

        async toggleSort(type) {
            try {
                this.$emit('toggleLoad',false)
                switch (this.sortType){
                    case 0: this.sortType = (type == 'name') ? 1 : 4; break
                    case 1: this.sortType = (type == 'name') ? 2 : 4; break
                    case 2: this.sortType = (type == 'name') ? 1 : 3; break
                    case 3: this.sortType = (type == 'name') ? 2 : 4; break
                    case 4: this.sortType = (type == 'name') ? 1 : 3; break
                    default: throw new Error(`Unsupported sort type (${this.sortType})`); break;
                }
                await this.sortItems(this.sortType);
                new URLHistory().add('sort',this.sortType)
                this.$emit('toggleLoad',true)
            } catch (e) {
                console.error(e.message)
            }
        },

        switchToCatalog() {
            window.location.href = "/Catalog";
        },

        async setDefault() {
            this.$emit('toggleLoad',false)
            this.title = "Каталог";
            new URLHistory().remove('group')
            this.breadCrumbs = [];
            await this.fetchCatalogGroups(
                `${window.location.origin}/api/catalog/base/groups`
            );
            if (this.sortType){
                await this.fetchSortedCatalogItems({ link: `${window.location.origin}/api/catalog/products`, sort: this.sortType });
            } else {
                await this.fetchCatalogItems(`${window.location.origin}/api/catalog/products`);
            }
            this.$emit('toggleLoad',true)
        },

        async back(index, item) {
            this.$emit('toggleLoad',false)
            let diff = this.breadCrumbs.length - index - 1;
            for (let i = 0; i < diff; i++) this.breadCrumbs.pop();
            await this.fetchCatalogGroups(item.subgroups);
            if (this.sortType){
                await this.fetchSortedCatalogItems({ link: item.subproducts, sort: this.sortType });
            } else {
                await this.fetchCatalogItems(item.subproducts);
            }
            this.active = "";
            this.$emit('toggleLoad',true)
        },

        async setGroup(item) {
            this.$emit('toggleLoad',false)
            new URLHistory().add('group',item.id)

            this.title = item.title;
            this.active = item.title;
            this.breadCrumbs = item.path

            await this.fetchCatalogGroups(item._links.subgroups.href);
            if (this.sortType){
                await this.fetchSortedCatalogItems({ link: item._links.subproducts.href, sort: this.sortType });
            } else {
                await this.fetchCatalogItems(item._links.subproducts.href);
            }
            this.$emit('toggleLoad',true)
        }
    },
    computed: {
        ...mapGetters("catalog", ["catalogFilters", "catalogGroups", "isEnd"]),
        title() {
            return this.$props.mode == "catalog"
                ? "Каталог"
                : `Результаты поиска по запросу ${this.$props.query}`;
        }
    },
    async created() {
        await this.fetchCatalogGroups(
            `${window.location.origin}/api/catalog/base/groups`
        );
    }
};
</script>

<style scoped lang = "scss">
.sort_active {
  color: #9d2f2f;
}
</style>