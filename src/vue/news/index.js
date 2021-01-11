import Vue from 'vue';
import Layout from './layout.vue';


export default class News {
	constructor(selector) {
		const el = document.querySelector(selector);
		new Vue({
			el: selector,
			render: h => h(Layout),
		});
	}

}
