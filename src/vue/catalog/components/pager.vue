<template lang="pug">

.pager(v-if="pagesNumber > 1")
  .pager__pages
    svg
        use(href='icons/icons.svg#arrow-prev')
    .pager__page(
        v-for="(pageNumber, index) in pagesNumber"
        v-if="isFirst(pageNumber)  || isActive(pageNumber) || isnNighbor(pageNumber) || isLast(pageNumber, pagesNumber)"
        @click="$emit('input', index)"
        :class="{'pager__page_active': index === offset}"
    ) {{pageNumber}} 
    //- .pager__dots ...
    svg
        use(href='icons/icons.svg#arrow-next')             
</template>
<script>

export default {
    props: {
        fetchNextPage: Function,
        pagesNumber: Number,
        offset: Number,
        limit: Number
    },

    data() {
        return {
            isActiveClass: false,
            itemNumber: []
        };
    },

    computed: {},

    methods: {
        isFirst(i) {
            return i <= 2;
        },
        isActive(i) {
            return i === this.offset + 1;
        },
        isnNighbor(i) {
            return i === this.offset + 2 || i === this.offset;
        },
        isLast(i, total) {
            return i > total - 1;
        }
    }
};
</script>
