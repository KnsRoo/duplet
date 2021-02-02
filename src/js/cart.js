import '../scss/cart.scss';
import headerSearch from './global';
import Cart from '../vue/cart';

document.addEventListener('DOMContentLoaded', () => {
    headerSearch()
    new Cart('#cart');
})

//for number

let buttonMinus = document.querySelectorAll('.button__minus');
let buttonPlus = document.querySelectorAll('.button__plus');
let inputNumber = document.querySelectorAll('.choose__number');


for (let i = 0; i < buttonPlus.length; i++) {
    buttonPlus[i].onclick = function () {

        for (let j = 0; j < inputNumber.length; j++) {

            let qty = parseInt(inputNumber[j].value);
            qty = qty + 1;
            inputNumber[j].value = qty;
        }
    }
}

for (let i = 0; i < buttonMinus.length; i++) {
    buttonMinus[i].onclick = function () {

        for (let j = 0; j < inputNumber.length; j++) {

            let qty = parseInt(inputNumber[j].value);
            qty = qty - 1;
            inputNumber[j].value = qty;
        }
    }
}


//for card__important

let cardImportant = document.querySelectorAll('.card__important');

for (let i = 0; i < cardImportant.length; i++) {
    cardImportant[i].onclick = function () {
        cardImportant[i].classList.toggle('card__important_active');
    }
}

