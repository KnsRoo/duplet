export default {
	orders(state) {
		return {
			items: state.orders._embedded.items,
			next: (state.orders._links.next) ? state.orders._links.next : null
		}
	},
}
