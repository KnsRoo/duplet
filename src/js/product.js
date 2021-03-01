import '../scss/card__item.scss';
import Product from '../vue/catalog/product';
import { initSwipersProduct } from './components/sliderConfig';
import init from './global';


document.addEventListener('DOMContentLoaded', () => {
    init()
    new Product('#productItem')
    // initSwipersProduct()
})