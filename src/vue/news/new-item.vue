<template lang = "pug">
.news__card
	.img__item
		img.img__news(:src="newsItem.picture" alt='')
	.news__card_title {{ newsItem.title }}
	.news__card_read
		a.news__link(:href='newsItem.pageRef')
			span.news__link_title Читать
		span.news__data {{ format(newsItem.creationDate) }}
</template>

<script>
export default {
    data() {
        return {};
    },
    methods: {
        format(toFormat) {
            let [date, time] = toFormat.split(" ");
            let [year, month, day] = date.split("-");
            return `${day}.${month}.${year}`;
        }
    },
    props: {
        newsItem: Object
    }
};
</script>