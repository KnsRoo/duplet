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
}
