import '../scss/favorite.scss';
import init from './global';
import Favorites from '../vue/favorites';
import refreshToken from './components/refreshToken'

document.addEventListener('DOMContentLoaded', async () => {
    init()
    try {
    	await refreshToken();
    	new Favorites('#favorites');
    } catch (err) {
    	console.log(err.message)
    	localStorage.removeItem("jwt");
    	window.modalLogin.toggle();
    return;
  	}
})
