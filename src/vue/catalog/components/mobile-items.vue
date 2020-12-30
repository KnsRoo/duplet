<template lang="pug">
        .mobile-catalog   
            .mobile-catalog__tilte.title Каталог
            .mobile-catalog__wrapper
              a.mobile-catalog-item(
                  v-for="mobileItem in mobileItems"
                  :key="mobileItem.id"
                  href="https://ffarm.1mcg.ru/assets/product-item.html"
                  )
                  .mobile-catalog-item__wrapper
                      .mobile-catalog-img(:style="{backgroundImage: `url(${mobileItem.picture})`}")
                  .mobile-catalog-title {{mobileItem.title}}
</template>

<script>
export default {
    // props: ["img"],
    data() {
        return {
            mobileItems: [
                {
                    id: 1,
                    title: "Лекарства",
                    // picture: "./images/catalog-item.png",
                    picture: img1
                },
                {
                    id: 2,
                    title: "БАДы",
                    picture: img2
                },
                {
                    id: 3,
                    title: "Оптика",
                    picture: img3
                },
                {
                    id: 4,
                    title: "Мед. техника",
                    picture: img4
                },
                {
                    id: 5,
                    title: "Гигиена",
                    picture: img5
                },
                {
                    id: 6,
                    title: "Ортопедия",
                    picture: img6
                },
                {
                    id: 7,
                    title: "Косметика",
                    picture: img7
                },
                {
                    id: 8,
                    title: "Здоровая еда",
                    picture: img8
                },
                {
                    id: 9,
                    title: "Мама и малыш",
                    picture: img9
                },
                {
                    id: 10,
                    title: "Акции",
                    picture: img10
                }
            ]
        };
    },
    mounted() {
        //   console.log(this)
    }
};
</script>
