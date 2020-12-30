export default function headerSearch() {
    // main page

    let burger = document.querySelector('.menu__btn');
    let headerPopup = document.querySelector('.header__popup');
    let lockScroll = document.querySelector('body')

    burger.onclick = function () {
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
}
