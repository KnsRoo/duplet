import Vue from 'vue';
import store from '../store';
import Layout from './layout.vue';


export default class News {
	constructor(selector) {
		const el = document.querySelector(selector);
		new Vue({
			el: selector,
			store,
			render: h => h(Layout),
		});
	}

}
