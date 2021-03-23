<template lang = "pug">
.news_wrapper
	.news__block
		.img__item
			img.img__news(v-if = "picture" :src="newsItem[0].picture" alt='')
			img.img__news(v-else src="/assets/img/default.png" alt='')
		.news__block_instruction
			.news__title {{ newsItem[0].title }}
			.news__text {{ newsItem[0].announce }}
			.news__card_read
				a.btn(:href='newsItem[0].pageRef')
					span.btn__name Читать
				span.news__data
					figure.icon-data
					.news__data_title {{ format(newsItem[0].creationDate) }}
	.news__cards
		.news__card(v-for="(item, index) in newsItem" v-if = "index !=0")
			.img__item
				img.img__news(v-if = "item.picture" :src="item.picture" alt='')
				img.img__news(v-else src="/assets/img/default.png" alt='')
			.news__title {{ item.title }}
			.news__text {{ item.announce }}
			.news__card_read
				a.btn(:href='item.pageRef')
					span.btn__name Читать
				span.news__data
					figure.icon-data
					.news__data_title {{ format(item.creationDate) }}
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
        newsItem: Array
    }
};
</script>