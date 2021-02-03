import {
    burgerToogler,
    searchToogler
} from './tooglers.js'

import ProfileIcons from '../../vue/profile-icons'

import ModalLogin from '../../vue/ModalLogin'

export default function initGlobalScripts() {
    window.profile = new ProfileIcons('#profile')
    new burgerToogler()
    new searchToogler({
        box: '.search__box',
        searchBtn: '.search__btn',
        cancelBtn: '.cancel__btn',
        input: '.js-query'
    },
        {
            '.search__box': 'active',
            '.input__search': 'input__active',
            '.search__btn': 'search__btn_active',
            '.cancel__btn': 'cancel__btn_active',
            '.phone': 'phone__hidden',
            '.profile': 'profile__hidden',
            '.nav': 'nav__hidden',
        })

    new searchToogler({
        box: '.search__box_mobile',
        searchBtn: '.search__btn_mobile',
        cancelBtn: '.cancel__btn_mobile',
        input: '.js-query-m'
    }, {
        '.search__box_mobile': 'active',
        '.input__search_mobile': 'input__active',
        '.search__btn_mobile': 'search__btn_active',
        '.cancel__btn_mobile': 'cancel__btn_active',
        '.block__first': 'block__first_hidden',
        '.mobile__block': 'mobile__block_active',
        '.block__second': 'block__second_active',
    })
    window.modalLogin = new ModalLogin();


    let linkCart = document.querySelector('.add__to__cart');
    let popUp = document.querySelector('.popup__card__item');
    let closeBtn = document.querySelector('.close__popup');


    // linkCart.onclick = () => {
    //     popUp.classList.add('open__popup');
    // }

    // closeBtn.onclick = () => {
    //     popUp.classList.remove('open__popup');

    // }
}
