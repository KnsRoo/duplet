import '../scss/index.scss';
import { initSwipers } from './components/sliderConfig'
import initGlobalScripts from './global'
import CatsWidget from '../vue/widgets/categories';
import Slider from '../vue/widgets/slider';


document.addEventListener('DOMContentLoaded', () => {
    initGlobalScripts();
    initSwipers();
    new CatsWidget('#categories');
    new Slider('#novelty');
    new Slider('#popular');
})