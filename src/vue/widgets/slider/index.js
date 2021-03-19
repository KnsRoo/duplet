import Vue from 'vue';
import Layout from './Layout.vue';


export default class {
	constructor(selector) {
		const el = document.querySelector(selector);
		new Vue({
			el: selector,
			render: h => h(Layout),
			props: { 
				id: el.getAttribute('data-id'),
				link: el.getAttribute('data-page-link'),
				title: el.getAttribute('data-title'),
				description: el.getAttribute('data-description') 
			}
		});
	}

}
