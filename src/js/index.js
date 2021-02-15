import '../scss/index.scss';
import { initSwipers } from './components/sliderConfig'
import initGlobalScripts from './global'


document.addEventListener('DOMContentLoaded', () => {
    initGlobalScripts();
    initSwipers()
})