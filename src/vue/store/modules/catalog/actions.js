import ky from 'ky'

export default {

	async fetchCatalogGroups({ commit }, link) {
		let result = await ky.get(link).json()
		commit('setCatalogGroups', result._embedded.items)
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

	async fetchCatalogItems({ commit }, link) {
		commit('setCurrent', link)
		let result = await ky.get(link).json()
		let items = result._embedded.items
		let next = result._links.next ? result._links.next.href : null
		commit('setCatalogProducts', { items, next})
	},

	async nextItems({ commit, state }) {
		let result = await ky.get(state.next).json()
		let items = result._embedded.items
		let next = result._links.next ? result._links.next.href : null
		commit('addCatalogProducts', { items, next})
	},

};