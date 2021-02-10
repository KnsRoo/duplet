<template lang="pug">
.product(:class = "{ discount: 'product.discount != null && product.discount != 0'}")
	.discount__percent(v-if="product.discount != null && product.discount != 0") {{ product.discount }} %
	.favorite__cross.icon-menu-cancel
	.product__block
		img.product__img(:src = "product.picture")
	.product__content
		.product__content_title(@click = "getProduct")
			.product__content_title_name {{ product.title }}
			.product__content_title_price(v-if = "product.discount != null && product.discount != 0")
				.main__price ₽ {{ product.price }}
				.discount__price(v-if= "product.discount != null && product.discount != 0") ₽ {{ product.discount_price }}
			//- .product__content_title_price(v-else)
			//- 	.discount__price ₽ {{ product.price }}
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
        getProduct() {
            window.location.href = this.$props.product.pageRef;
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
