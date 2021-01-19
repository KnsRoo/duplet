<template lang="pug">
.filter_wrapper
    .catalog-guns__title {{ title }}
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
                    .sort__name_title  Названию
                    img(v-if = "[0,3,4].includes(sortType)" src = "/assets/img/icons/sort_neitral.svg")
                    img(v-else-if = "sortType == 1" src="/assets/img/icons/sort_down.svg")
                    img(v-else-if = "sortType == 2" src = "/assets/img/icons/sort_up.svg")
                    //- .sort__up.icon-filter
                    //- .sort__down.icon-filter
                .sort__price(@click = "toggleSort('price')")
                    .sort__price_title  Цене
                    img(v-if = "[0,1,2].includes(sortType)" src = "/assets/img/icons/sort_neitral.svg")
                    img(v-else-if = "sortType == 3" src="/assets/img/icons/sort_down.svg")
                    img(v-else-if = "sortType == 4" src = "/assets/img/icons/sort_up.svg")
                    //- .sort__up.icon-filter
                    //- .sort__down.icon-filter
    .catalog__cat(v-else)
        .catalog__cat__show(@click = "switchToCatalog")
            img.cat_button( src="/assets/img/icons/menu-cancel.svg")
            .catalog__cat__title В каталог
    .catalog__cat__list(v-if="show")
        .catalog__cat__item(v-for="item in catalogGroups" @click = "setGroup(item)" :class = "{active: item.title == active}")
            .catalog__cat__item_title {{ item.title }}

</template>

<script>
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
            active: ''
        };
    },

    methods: {
        ...mapActions("catalog", ["fetchCatalogGroups", "fetchCatalogItems"]),
        ...mapMutations("catalog", ["setFilterGroupId", "setGroupId"]),

        toggleCats() {
            this.show = !this.show;
        },
        toggleSort(type) {
            switch (this.sortType){
                case 0: this.sortType = (type == 'name') ? 1 : 4; break
                case 1: this.sortType = (type == 'name') ? 2 : 4; break
                case 2: this.sortType = (type == 'name') ? 1 : 3; break
                case 3: this.sortType = (type == 'name') ? 2 : 4; break
                case 4: this.sortType = (type == 'name') ? 1 : 3; break
            }
            console.log(this.sortType)
        },

        switchToCatalog() {
            window.location.href = "/Catalog";
        },

        async setDefault() {
            this.title = "Каталог";
            this.breadCrumbs = [];
            await this.fetchCatalogGroups(
                `${window.location.origin}/api/catalog/base/groups`
            );
        },

        async back(index, item) {
            let diff = this.breadCrumbs.length - index - 1;
            for (let i = 0; i < diff; i++) this.breadCrumbs.pop();
            await this.fetchCatalogGroups(item._links.subgroups.href);
            await this.fetchCatalogItems(item._links.subproducts.href);
            this.active = ''
        },

        async setGroup(item) {
            this.title = item.title;
            this.active = item.title;
            if (this.isEnd){
                this.breadCrumbs.pop();
            }
            this.breadCrumbs.push(item);
            await this.fetchCatalogGroups(item._links.subgroups.href);
            await this.fetchCatalogItems(item._links.subproducts.href);
            
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