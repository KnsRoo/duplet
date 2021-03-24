export default {
	orders(state) {
		return {
			items: state.orders,
			next: state.next
		}
	},
}
