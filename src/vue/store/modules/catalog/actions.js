import ky from 'ky'

export default {

	async fetchCatalogGroups({ commit }, link) {
		let result = await ky.get(link).json()
		commit('setCatalogGroups', result._embedded.items)
	},

	async fetchCatalogItems({ commit }, link) {
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