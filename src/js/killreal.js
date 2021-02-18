import { initSwipers } from './components/sliderConfig';


document.addEventListener('DOMContentLoaded', () => {
    // initGlobalScripts();
    initSwipers()
})


let lengths = ["Bilbo", "Gandalf", "Nazgul"].map(item => item + 'suck');
console.log(lengths);
