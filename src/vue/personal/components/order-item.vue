<template lang = "pug">
.card
    .card__des.card__info
        .card__wrap
            .card__img
                .product
                    .discount__percent(v-if="product.discount") {{ product.discount }}
                    .favorite__cross.icon-menu-cancel
                    .product__block
                        img.product__img(:src="product.picture", alt="")
            .card__text
                .card__text_title {{ product.title }}
                .card__text_block
                    .card__brand
                        .card__brand_title Бренд:
                        .card__brand_name ТОЗ
                    .card__caliber
                        .card__caliber_title Калибр:
                        .card__caliber_name 12/70
    .card__cost.card__info ₽ {{ product.price }}
    .card__cost.card__info {{ product.count }}
    .card__cost.card__info {{ product.discount == '%' ? '0%' : product.discount }}
</template>

<script>
export default {
	data(){
		return {}
	},
	props: {
		product: Object
	}
}
</script>