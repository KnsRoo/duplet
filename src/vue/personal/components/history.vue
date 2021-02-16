<template lang="pug">
article.buy__history
	.wrapper
		.history__title История покупок
		input.history__input
		.history__box
</template>

<script>
export default {
    data() {
        return {
        };
    },
    async created(){
    	this.$emit('toggleLoad', { component: 'history', value: true})
    }
}
</script>