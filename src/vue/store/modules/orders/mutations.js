export default {
	setOrders(state, {orders, next}){
		state.orders = orders
		state.next = next
	},
	addOrders(state, {orders, next}){
		state.orders.push(orders)
		state.next = next
	}
}