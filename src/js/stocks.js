import '../scss/discount.scss';
import initGlobalScripts from './global'
import Stocks from '../vue/stocks';


document.addEventListener('DOMContentLoaded', () => {
    initGlobalScripts();
    new Stocks('#stocks');
})
