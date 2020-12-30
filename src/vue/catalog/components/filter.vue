<template lang="pug">
.filter_wrapper
    .catalog-guns__title Каталог
    .catalog__filter
        .filter__show(v-if="show")
            figure.icon-menu-burger
            .filter_show_title Показать категории
            .filter__show_number 5
        .filter_sort
            .filter__sort_title Сортировка по:
            .filter__sort_type Популярности
</template>

<script>
import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
    props: {
        filters: Array
    },
    data() {
        return {
            show: true
            // filters: []
        };
    },

    methods: {
        ...mapActions("catalog", ["fetchFilters","getFilterGroupId","getGroupId"]),
        ...mapMutations("catalog", ["setFilterGroupId" ,"setGroupId"]),
        
        clickOnitem(id) {
            this.setGroupId(id);
            this.$parent.loadProducts();
            console.log('filter ',id);
        }
    },
    computed: {
        ...mapGetters("catalog", ["catalogFilters"])
    },
    created() {
        this.fetchFilters();
    }
};
</script>
