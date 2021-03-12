<template lang = "pug">
.slider__box
    .swiper-container.slider__sidebar(ref="slider-sidebar" v-if = "images.length > 1")
        .swiper-wrapper
            .swiper-slide(v-for = "item in images")
                .slider__sidebar_box
                    img.slider__sidebar_img(:src="item" alt='')
    .swiper-container.slider__main(ref="slider-main")
        .swiper-wrapper
            .swiper-slide(v-for = "item in images")
                .slider__main_box
                    img.slider__main_img(:src="item" alt='')
        .swiper-button-prev
        .swiper-button-next
</template>

<script>
import { getConfig } from "../../../../js/components/sliderConfig";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";

export default {
    data() {
        return {
            cardItemSidebarSwiper: undefined,
            cardItemMainSwiper: undefined
        };
    },
    props: {
        images: Array
    },
    computed: {},
    methods: {
        initSlider() {
            if (this.$props.images.length > 1){
                this.cardItemSidebarSwiper = new Swiper(
                    this.$refs["slider-sidebar"],
                    getConfig().cardItemSidebar
                );
            }
            let config = getConfig().cardItemMain;
            config.thumbs = { swiper: this.cardItemSidebarSwiper };
            this.cardItemMainSwiper = new Swiper(
                this.$refs["slider-main"],
                config
            );
        }
    },
    mounted() {
        this.initSlider();
        setTimeout(() => {
            if (this.$props.images.length > 1){
                this.cardItemSidebarSwiper.update();
            }
            this.cardItemMainSwiper.update();
        }, 500);
    },
    created() {}
};
</script>

<style scoped lang = "scss">
</style>