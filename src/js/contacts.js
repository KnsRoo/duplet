import '../scss/contacts.scss';
import { initSwipers } from './components/sliderConfig'
import initGlobalScripts from './global'

document.addEventListener('DOMContentLoaded', () => {
    initGlobalScripts();
    initSwipers();
})

