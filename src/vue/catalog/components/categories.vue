<template lang="pug">
.filter_wrapper
    .catalog-guns__title {{ title }}
    .catalog__cat
        .catalog__cat__show(@click = "toggleCats()")
            img.cat_button(v-if = "show" src="/assets/img/icons/menu-cancel.svg")
            img.cat_button(v-else src="/assets/img/icons/menu-burger.svg")
            .catalog__cat__title {{ (show) ? 'Скрыть категории' : 'Показать категории' }}
        .catalog__sort
            .catalog__sort_title Сортировка по:
            .catalog__sort_type Популярности
    .catalog__cat__list(v-if="show")
        .catalog__cat__item(v-for="item in catalogGroups" @click = "setGroup(item)")
            .catalog__cat__item_title {{ item.title }}

</template>

<script>
import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
    props: {
        filters: Array
    },
    data() {
        return {
            show: true,
            title: 'Каталог',
            cats: []
        };
    },

    methods: {
        ...mapActions("catalog", ["fetchCatalogGroups"]),
        ...mapMutations("catalog", ["setFilterGroupId" ,"setGroupId"]),

        toggleCats(){
            this.show = !this.show
        },
        
        setGroup(item){
            this.title = item.title
            this.fetchCatalogGroups(item._links.subgroups.href)
        }
    },
    computed: {
        ...mapGetters("catalog", ["catalogFilters", "catalogGroups"])
    },
    async created() {
        await this.fetchCatalogGroups(`${window.location.origin}/api/catalog/base/groups`);
        this.cats = this.catalogGroups;
    }
};
</script>