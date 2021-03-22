import Vue from 'vue';
import Layout from './Layout.vue';
import store from '../../store'


export default class {
	constructor(selector) {
		const el = document.querySelector(selector);
		new Vue({
			el: selector,
			store,
			render: h => h(Layout),
			settings: { 
				id: el.getAttribute('data-id'),
				link: el.getAttribute('data-link'),
				title: el.getAttribute('data-title'),
				description: el.getAttribute('data-description') 
			}
		});
	}

}
