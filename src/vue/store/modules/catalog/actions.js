import ky from 'ky'

export default {

	async fetchCatalogGroups({ commit }, link) {
		let result = await ky.get(link).json()
		console.log(result)
		commit('setCatalogGroups', result._embedded.items)
	},

	async fetchCatalogItems({ commit }, link) {
		let result = await ky.get(link).json()
		console.log(result)
		commit('setCatalogProducts', result._embedded.items)
	},

};