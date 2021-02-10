import ky from 'ky'

export default {
	async appendOrder(ctx, order){
		// let result = await ky.post(`${window.location.origin}/api/orders`, json: order)
	},
	async fetchOrders({commit}){
		// let result = await ky.get(`${window.location.origin}/api/user/orders`).json()
		// commit("setOrders", result._embedded.items)
	},
	async fetchNextOrders({getters, commit}){
		// let result = await ky.get(`${getters.next}`).json()
		// commit("addOrders", result._embedded.items)	
	}
}