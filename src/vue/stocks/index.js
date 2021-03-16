import Vue from 'vue';
import Layout from './layout.vue';


export default class Stocks {
	constructor(selector) {
		const el = document.querySelector(selector);
		new Vue({
			el: selector,
			render: h => h(Layout),
			link: el.getAttribute('data-page-link')
		});
	}

}
