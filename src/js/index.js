import '../scss/index.scss';
import { initSwipers } from './components/sliderConfig'
import initGlobalScripts from './global'
import CatsWidget from '../vue/widgets/categories';


document.addEventListener('DOMContentLoaded', () => {
    initGlobalScripts();
    initSwipers();
    new CatsWidget('#categories');
})