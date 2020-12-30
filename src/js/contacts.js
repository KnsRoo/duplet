import headerSearch from './global';

document.addEventListener('DOMContentLoaded', () => {
    headerSearch()
})


import Swiper, { Navigation, Pagination } from 'swiper';
import 'swiper/swiper-bundle.css';
import '../scss/contacts.scss';

Swiper.use([Navigation, Pagination]);

var galleryThumbs = new Swiper('.location__slider', {
    slidesPerView: 1,
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
    scrollbar: {
        el: '.swiper-scrollbar',
    }
});

