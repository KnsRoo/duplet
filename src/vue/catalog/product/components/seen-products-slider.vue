<template lang = "pug">
swiper(ref="slider" :options = "options")
    swiper-slide(v-for = "(item, key) in products" :key = "key")
        Product(:product = "item")
    .swiper-pagination(slot = "pagination")
</template>

<script>
import ky from "ky";
import { getConfig } from "../../../../js/components/sliderConfig";
import Seen from "../../../../js/components/seenStore";
import Product from "../../components/catalog-item.vue";
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper'
import 'swiper/swiper-bundle.css'

export default {
    data() {
        return {
            products: [],
        };
    },
    components: {
        Product,
        Swiper,
        SwiperSlide
    },
    computed: {
      swiper() {
        return this.$refs.slider.$swiper
      },
      options(){
        return getConfig().imgs
      }
    },
    async created() {
        let products = new Seen("seenList").get();
        if (products) {
            products.forEach(async val => {
                let product = await ky.get(val).json();
                this.products.push(product);
            });
        }
    }
};
</script>
