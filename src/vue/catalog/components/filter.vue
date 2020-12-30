<template lang="pug">
    .catalog__filter
        .catalog__filter__tilte
            h1 Лекарства
        ul
            li(v-for="(filter, key) in catalogFilters")
                label(@click="clickOnitem(filter.id)" :key="key")
                    .checkbox
                        input(type="radio" name="filter")
                        .checkbox__mark
                    span {{filter.title}}
</template>

<script>
import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
    props: {
        filters: Array
    },
    data() {
        return {
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
