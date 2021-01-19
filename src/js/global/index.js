import { burgerToogler, 
         searchToogler,
         mobileSearchToogler 
} from './tooglers.js'

import ModalLogin from '../../vue/ModalLogin'

export default function initGlobalScripts() {
    new burgerToogler()
    new searchToogler()
    new mobileSearchToogler()
    window.modalLogin = new ModalLogin()

    const rules = {
    	'.js-search' : '.js-query',
    	// '.js-search-m' : '.js-query-m'
    }
    for (let [k, v] of Object.entries(rules)){
    	const button = document.querySelector(k),
    		  input  = document.querySelector(v);

    	button.addEventListener('click', (e) => {
    		if (input.classList.contains('input__active')){
    			window.location.href = `/catalog?query=${input.value}`
    		}
    		
    	})
    }
}
