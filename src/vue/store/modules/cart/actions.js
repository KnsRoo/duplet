import ky from 'ky'
import authfetch from '../../../../js/components/authfetch'
import noty from "../../../../js/components/noty";

export default {
	async fetchItems({commit}){
		let result = await ky.get(`${window.location.origin}/api/cart/items`).json()
		commit("setItems", result)
	},
	async addToCart({dispatch}, item){
		let result = await ky.post(`${window.location.origin}/api/cart/items`, { json: item } )
		dispatch("fetchItems")
	},
	async removeFromCart({dispatch}, itemId){
		let result = await ky.delete(`/api/cart/items/${itemId}`)
		dispatch("fetchItems")
	},
	async updateItemCount({dispatch}, {id, count}){
		let result = await ky.patch(`${window.location.origin}/api/cart/items/${id}`, { json: [{ op: "add", path: "/count", value: count }] })
	}
}