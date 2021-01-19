import '../scss/lk.scss';
import headerSearch from './global';
//import ModalLogin from '../vue/ModalLogin'
import PersonalArea from '../vue/personal'
import refreshToken from './components/refreshToken'

document.addEventListener('DOMContentLoaded', async () => {
    headerSearch()
    //let modalLogin = new ModalLogin()
    try {
    	await refreshToken();
    	new PersonalArea('#personal')
    } catch (err) {
    	localStorage.removeItem("jwt");
    	window.modalLogin.toggle();
    return;
  	}
})
