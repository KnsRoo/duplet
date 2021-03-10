export default {
	setUser(state, data) {
		state.user = data
	},
	setUserProps(state, data) {
		state.properties = data
	},
	setUserOrders(state, data) {
		console.log('setting data')
		state.orders = data
	},
}