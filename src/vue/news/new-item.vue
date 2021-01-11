<template lang = "pug">
.news__card
	.img_wrapper
		img.news__card_img(:src="newsItem.picture" alt='')
	.news__card_title {{ newsItem.title }}
	.news__card_read
		a.news__card_read_title(:href='newsItem.pageRef') Читать
		.news__card_read_data
			img.news__card_read_data_svg(src="/assets/img/icons/data.svg" alt='')
			.news__card_read_data_number {{ format(newsItem.creationDate) }}
</template>

<script>
export default {
	data(){
		return{
		}
	},
	methods: {
		format(toFormat){
			let [ date, time] = toFormat.split(' ')
			let [ year, month, day ] = date.split('-')
			return `${day}.${month}.${year}`
		}
	},
	props: {
		newsItem: Object
	}
}
</script>