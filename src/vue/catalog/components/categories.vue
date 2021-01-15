<template lang="pug">
.filter_wrapper
    .catalog-guns__title {{ title }}
    .catalog__cat(v-if = "mode == 'catalog'")
        .catalog__cat__show(@click = "toggleCats()")
            img.cat_button(v-if = "show" src="/assets/img/icons/menu-cancel.svg")
            img.cat_button(v-else src="/assets/img/icons/menu-burger.svg")
            .catalog__cat__title {{ (show) ? 'Скрыть категории' : 'Показать категории' }}
        .breadcrumbs__wrapper(v-if = "breadCrumbs")
            .breadcrumbs(v-for="(item, index) in breadCrumbs")
                a.link(v-if="index == 0" @click = "setDefault()") Все категории
                span.separator >
                a.link(v-if = "index != breadCrumbs.length-1" @click = "back(index,item)") {{ item.title }}
                a.link__last(v-else) {{ item.title }}
        .catalog__sort
            .catalog__sort_title Сортировка по:
            .catalog__sort_type Популярности
    .catalog__cat(v-else)
        .catalog__cat__show(@click = "switchToCatalog")
            img.cat_button( src="/assets/img/icons/menu-cancel.svg")
            .catalog__cat__title В каталог
    .catalog__cat__list(v-if="show")
        .catalog__cat__item(v-for="item in catalogGroups" @click = "setGroup(item)")
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
            show: true,
            breadCrumbs: []
        };
    },

    methods: {
        ...mapActions("catalog", ["fetchCatalogGroups", "fetchCatalogItems"]),
        ...mapMutations("catalog", ["setFilterGroupId" ,"setGroupId"]),

        toggleCats(){
            this.show = !this.show
        },

        switchToCatalog(){
            window.location.href = '/Catalog'
        },

        async setDefault(){
            this.title = 'Каталог'
            this.breadCrumbs = []
            await this.fetchCatalogGroups(`${window.location.origin}/api/catalog/base/groups`);
        },

        async back(index, item){
            let diff = this.breadCrumbs.length - index - 1
            for (let i = 0; i<diff; i++) this.breadCrumbs.pop()
            await this.fetchCatalogGroups(item._links.subgroups.href)
            await this.fetchCatalogItems(item._links.subproducts.href)
        },
        
        async setGroup(item){
            this.title = item.title
            this.breadCrumbs.push(item)
            await this.fetchCatalogGroups(item._links.subgroups.href)
            await this.fetchCatalogItems(item._links.subproducts.href)
        }
    },
    computed: {
        ...mapGetters("catalog", ["catalogFilters", "catalogGroups"]),
        title(){
            return (this.$props.mode == 'catalog') ? 'Каталог' : `Результаты поиска по запросу ${this.$props.query}`
        }
    },
    async created() {
        await this.fetchCatalogGroups(`${window.location.origin}/api/catalog/base/groups`);
    }
};
</script>