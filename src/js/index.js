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
    document.getElementById('warning').style = "display: flex; width: 100%; justify-content: right; background: #9d2f2f;"
    document.getElementById('closeWarning').onclick = function (){ document.querySelector('#warning').style = "display: none; width: 100%; justify-content: right; background: #9d2f2f;" }
})