export default {
	setOrders(state, data){
		state.orders = data._embedded.items
		state.next = data._links.next ? data._links.next.href : null
	},
	addOrders(state, data){
		state.orders = state.orders.concat(data._embedded.items)
		state.next = data._links.next ? data._links.next.href : null
	}
}