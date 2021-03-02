import '../scss/card__item.scss';
import Product from '../vue/catalog/product';
import { initSwipers } from './components/sliderConfig';
import init from './global';


document.addEventListener('DOMContentLoaded', () => {
    // initSwipers()
    init()
    new Product('#productItem')
})