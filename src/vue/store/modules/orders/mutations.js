export default {
	setOrders(state, data){
		state.orders = data
	},
	addOrders(state, data){
		state.orders.push(data)
	}
}