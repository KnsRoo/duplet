<template lang="pug">
.order__wrapper
	.history__title {{ orderProps['Имя'] }}
	.history__title {{ orderProps['Код операции'] }}
	.history__title {{ orderProps['Дата'] }}
	.history__title {{ orderProps['Адрес'] }}
	.history__title {{ orderProps['Скидка'] }}
	.history__title {{ orderProps['Сумма'] }}
	.history__title {{ orderProps['Статус'] }}
	.history__box
		ProductItem(v-for="item in orderProps['Товары']")

</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import ProductItem from './order-item.vue'

export default {
	data() {
		return {
		};
	},
	components: {
		ProductItem
	},
	props: {
		orderProps: Object
	}
}
</script>