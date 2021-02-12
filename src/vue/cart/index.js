import Vue from 'vue';
import Vuelidate from 'vuelidate'
import VueTheMask from 'vue-the-mask'
import store from '../store';
import Layout from './layout.vue';

Vue.use(Vuelidate)
Vue.use(VueTheMask)

export default class Cart {
	constructor(selector) {
		const el = document.querySelector(selector);
		new Vue({
			el: selector,
			store,
			render: h => h(Layout),
		});
	}

}
