import '../scss/lk.scss';
import init from './global';
import PersonalArea from '../vue/personal'
import refreshToken from './components/refreshToken'

document.addEventListener('DOMContentLoaded', async () => {
    init()
    try {
    	await refreshToken();
    	new PersonalArea('#personal')
    } catch (err) {
    	localStorage.removeItem("jwt");
    	window.modalLogin.toggle();
    return;
  	}
})
