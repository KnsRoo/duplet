import ky from 'ky';
import Vue from 'vue'
import ticon from './Layout.vue'
import store from '../store'

export default class{
	constructor(el){
		new Vue({
			el,
			store,
			render: h => h(ticon)
		})
	}
}