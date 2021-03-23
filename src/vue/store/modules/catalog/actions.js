import ky from 'ky'
import noty from '../../../../js/components/noty';

export default {

	async fetchCatalogGroups({ commit }, link) {
		let result = await ky.get(link).json()
		if (result.status === 'error') {
			noty('error','Ошибка при получении данных. Код ошибки 1441')
			return
		}
		commit('setCatalogGroups', result._embedded.items)
	},

	async fetchCatalogCounts({commit}){
		let result = await ky.get(`${window.location.origin}/api/catalog/basegroups/count`).json()
		if (result.status === 'error') {
			noty('error','Ошибка при получении данных. Код ошибки 1445')
			return
		}
		commit('setCatalogCounts', result.total)
	},

	async sortItems({dispatch, getters}, type){
		let params = null;
		switch(type){
			case 1: {
				params = {order: 'title', sort: 'ASC'}
			} break;
			case 2: {
				params = {order: 'title', sort: 'DESC'}
			} break;
			case 3: {
				params = {order: 'price', sort: 'ASC'}
			} break;
			case 4: {
				params = {order: 'price', sort: 'DESC'}
			} break;
		}
		let link = new URL(getters.current)
		link.searchParams.delete('offset')
		link.searchParams.delete('limit')
		link.searchParams.append('order',params.order)
		link.searchParams.append('sort',params.sort)
		await dispatch('fetchCatalogItems', link)

	},

	async fetchSortedCatalogItems({commit, dispatch}, {link, sort}){
		commit('setCurrent', link)
		await dispatch('sortItems', sort)
	},

	async fetchCatalogItems({ commit }, link) {
		commit('setCurrent', link)
		let result = await ky.get(link).json()
		if (result.status === 'error') {
			noty('error','Ошибка при получении данных. Код ошибки 1442')
			return
		}
		let items = result._embedded.items
		let next = result._links.next ? result._links.next.href : null
		commit('setCatalogProducts', { items, next})
	},

	async nextItems({ commit, state }) {
		let result = await ky.get(state.next).json()
		if (result.status === 'error') {
			noty('error','Ошибка при получении данных. Код ошибки 1443')
			return
		}
		let items = result._embedded.items
		let next = result._links.next ? result._links.next.href : null
		commit('addCatalogProducts', { items, next})
	},
	async fetchProduct({commit}, link){
		let result = await ky.get(link).json()
		if (result.status === 'error') {
			noty('error','Ошибка при получении данных. Код ошибки 1444')
			return
		}
		commit('setProduct', result)
	}

};