import ky from 'ky';
import Vue from 'vue'
import ticon from './Layout.vue'

export default class{
	constructor(el){
		new Vue({
			el,
			render: h => h(ticon)
		})
	}
}