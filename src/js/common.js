import Swiper, { Navigation, Pagination } from 'swiper';
Swiper.use([Navigation, Pagination]);


var mySwiper = new Swiper('.image__slider', {
    direction: 'horizontal',
    loop: true,
    slidesPerView: 4,
    spaceBetween: 20,
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
    scrollbar: {
        el: '.swiper-scrollbar',
    },
    breakpoints: {
        220: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 40,
        },
        992: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1200: {
            slidesPerView: 4,
            spaceBetween: 15,
        },
    }
})

var mySwiper = new Swiper('.tidings__slider', {
    direction: 'horizontal',
    loop: true,
    slidesPerView: 1,
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
    scrollbar: {
        el: '.swiper-scrollbar',
    },
});


var galleryThumbs = new Swiper('.gallery-thumbs', {
    spaceBetween: 10,
    slidesPerView: 4,
    loop: true,
    freeMode: true,
    loopedSlides: 5, //looped slides should be the same
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
});

var galleryTop = new Swiper('.gallery-top', {
    spaceBetween: 10,
    loop: true,
    loopedSlides: 5, //looped slides should be the same
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    thumbs: {
        swiper: galleryThumbs,
    },
});

var galleryThumbs = new Swiper('.location__slider', {
    slidesPerView: 1,
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
});


var mySwiper = new Swiper('.category__slider', {
    direction: 'horizontal',
    loop: true,
    slidesPerView: 4,
    spaceBetween: 20,
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
    scrollbar: {
        el: '.swiper-scrollbar',
    },
    breakpoints: {
        220: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 40,
        },
        992: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1200: {
            slidesPerView: 3,
            spaceBetween: 15,
        },
    }
})

// main page

let burger = document.querySelector('.menu__btn');
let headerPopup = document.querySelector('.header__popup');
let lockScroll = document.querySelector('body')

burger.onclick = function() {
    burger.classList.toggle('menu__btn_active');
    headerPopup.classList.toggle('header__popup_show');
    lockScroll.classList.toggle('lock__scroll');
}

// search

let searchBtn = document.querySelector('.search__btn');
let cancelhBtn = document.querySelector('.cancel__btn');
let searchBox = document.querySelector('.search__box');
let searchInput = document.querySelector('.input__search');
let hiddenPnone = document.querySelector('.phone');
let hiddenProfile = document.querySelector('.profile');
let hiddenNav = document.querySelector('.nav');

searchBtn.onclick = () => {
    searchBox.classList.add('active');
    searchInput.classList.add('input__active');
    searchBtn.classList.add('search__btn_active');
    cancelhBtn.classList.add('cancel__btn_active');
    hiddenPnone.classList.add('phone__hidden')
    hiddenProfile.classList.add('profile__hidden');
    hiddenNav.classList.add('nav__hidden');
}

cancelhBtn.onclick = () => {
    searchBox.classList.remove('active');
    searchInput.classList.remove('input__active');
    searchBtn.classList.remove('search__btn_active');
    cancelhBtn.classList.remove('cancel__btn_active');
    hiddenPnone.classList.remove('phone__hidden')
    hiddenProfile.classList.remove('profile__hidden');
    hiddenNav.classList.remove('nav__hidden');
}

// search mobile 

let searchBtnMobile = document.querySelector('.search__btn_mobile');
let cancelhBtnMobile = document.querySelector('.cancel__btn_mobile');
let searchBoxMobile = document.querySelector('.search__box_mobile');
let searchInputMobile = document.querySelector('.input__search_mobile');
let blockFirst = document.querySelector('.block__first');
let mobileBlock = document.querySelector('.mobile__block');
let blockSecond = document.querySelector('.block__second');

searchBtnMobile.onclick = () => {
    searchBoxMobile.classList.add('active');
    searchInputMobile.classList.add('input__active');
    searchBtnMobile.classList.add('search__btn_active');
    cancelhBtnMobile.classList.add('cancel__btn_active');
    blockFirst.classList.add('block__first_hidden');
    mobileBlock.classList.add('mobile__block_active');
    blockSecond.classList.add('block__second_active');
}

cancelhBtnMobile.onclick = () => {
    searchBoxMobile.classList.remove('active');
    searchInputMobile.classList.remove('input__active');
    searchBtnMobile.classList.remove('search__btn_active');
    cancelhBtnMobile.classList.remove('cancel__btn_active');
    blockFirst.classList.remove('block__first_hidden');
    mobileBlock.classList.remove('mobile__block_active');
    blockSecond.classList.remove('block__second_active');
}