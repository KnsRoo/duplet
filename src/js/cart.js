import '../scss/cart.scss';
import headerSearch from './global';
import Cart from '../vue/cart';

document.addEventListener('DOMContentLoaded', () => {
    headerSearch()
    new Cart('#cart');
})
