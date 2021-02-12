import ky from 'ky'
import authfetch from '../../../../js/components/authfetch'
import noty from "../../../../js/components/noty";

export default {
	async fetchItems({commit}){
		let result = await ky.get(`${window.location.origin}/api/cart/items`).json()
		commit("setItems", result)
	},
	async addToCart({dispatch}, item){
		let result = await ky.post(`${window.location.origin}/api/cart/items`, { 
			json: item,
			hooks: {
				afterResponse: [
					async (request, options, response) => {
						if (response.status === 409) {
							noty('warning', 'Товар уже в корзине')
						}
					}
				]
			}
			} )
		dispatch("fetchItems")
		noty('sucсess', 'Товар добавлен в корзину')
	},
	async removeFromCart({dispatch}, itemId){
		let result = await ky.delete(`/api/cart/items/${itemId}`)
		dispatch("fetchItems")
		noty('sucсess', 'Товар удален из корзины')
	},
	async updateItemCount({dispatch}, {id, count}){
		let result = await ky.patch(`${window.location.origin}/api/cart/items/${id}`, { json: [{ op: "add", path: "/count", value: count }] })
		await dispatch('fetchItems')
	}
}