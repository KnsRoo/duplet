<template lang="pug">
.filter_wrapper
    .catalog-guns__title Результаты поиска по запросу {{ query }}
    .catalog__cat
        .catalog__cat__show(@click = "switchToCatalog")
            img.cat_button( src="/assets/img/icons/menu-cancel.svg")
            .catalog__cat__title В каталог
        .catalog__sort
            .catalog__sort_title Сортировка по:
            .catalog__sort_type Популярности

</template>

<script>
import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
    props: {
        query: String
    },
    data() {
        return {
            show: true,
            title: 'Каталог',
        };
    },

    methods: {
        ...mapActions("catalog", ["fetchCatalogGroups", "fetchCatalogItems"]),
        ...mapMutations("catalog", ["setFilterGroupId" ,"setGroupId"]),

        toggleCats(){
            this.show = !this.show
        },
        
        async setGroup(item){
            this.title = item.title
            await this.fetchCatalogGroups(item._links.subgroups.href)
            await this.fetchCatalogItems(item._links.subproducts.href)
        }
    },
    computed: {
        ...mapGetters("catalog", ["catalogFilters", "catalogGroups"])
    },
    async created() {
        await this.fetchCatalogGroups(`${window.location.origin}/api/catalog/base/groups`);
    }
};
</script>