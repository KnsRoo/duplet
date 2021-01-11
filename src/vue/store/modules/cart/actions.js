import ky from 'ky'

export default {
	async fetchItems({commit}){
		let result = await ky.get(`/api/cart/items`).json()
		commit("setItems", result)
	},
	async addToCart({dispatch}, item){
		let result = await ky.post(`/api/cart/items`, { json: item } )
		dispatch("fetchItems")
	},
	async removeFromCart({dispatch}, itemId){
		let result = await ky.delete(`/api/cart/items/${itemId}`)
		dispatch("fetchItems")
	},
	async updateItemCart({dispatch}, itemId){
		let result = await ky.patch(`/api/cart/items/${itemId}`)
		dispatch("fetchItems")
	}
}