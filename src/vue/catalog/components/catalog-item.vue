<template lang="pug">
.card
	.discount__percent(v-if="product.props.discount") {{ product.props.discount.value }}
	.favorite__cross.icon-menu-cancel
	.card__block
		img.card__img(:src = "product.picture")
	.card__content
		.card__content_title
			.card__content_title_name {{ product.title.replace('\"','"') }}
			.card__content_title_price
				.first__price {{ product.price+' ₽' }}
				.discount__price(v-if= "product.props.discount") {{ product.props.discount.value+' ₽' }}
		figure.icon-add(@click="addItemToCart")
</template>

<script>
import { mapActions } from "vuex";
export default {
    props: {
        product: Object
    },

    methods: {
        ...mapActions("cart", ["addToCart"]),
        addItemToCart() {
            const dataItem = {
                id: this.product.id,
                count: this.product.count
            };
            this.addToCart(dataItem);
        },
        addCart() {
            if (this.product.count < 100) {
                this.product.count++;
                this.product.total = this.product.count * this.product.price;
            } else {
                return;
            }
        },
        minCart() {
            if (this.product.count <= 1) {
                return;
            } else {
                this.product.count--;
                this.product.total -= this.product.price;
            }
        }
    },
    computed: {
        itemImage() {
            return this.product.picture;
        }
    }
};
</script>
