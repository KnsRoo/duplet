<template lang="pug">
.filter_wrapper
    .catalog-guns__title {{ title }}
    .catalog__cat
        .catalog__cat__show(@click = "toggleCats()")
            .menu__btn(v-bind:class='{menu__btn_active: show }')
                span
                span
                span
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
            show: false,
            title: "Каталог"
        };
    },

    methods: {
        ...mapActions("catalog", ["fetchCatalogGroups", "fetchCatalogItems"]),
        ...mapMutations("catalog", ["setFilterGroupId", "setGroupId"]),

        toggleCats() {
            this.show = !this.show;
        },

        async setGroup(item) {
            this.title = item.title;
            await this.fetchCatalogGroups(item._links.subgroups.href);
            await this.fetchCatalogItems(item._links.subproducts.href);
        }
    },
    computed: {
        ...mapGetters("catalog", ["catalogFilters", "catalogGroups"])
    },
    async created() {
        await this.fetchCatalogGroups(
            `${window.location.origin}/api/catalog/base/groups`
        );
    }
};
</script>