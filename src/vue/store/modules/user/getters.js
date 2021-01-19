export default {
	getUser(state) {
		return {
			name: (state.properties.name) ? state.properties.name : '',
			phone: (state.user.phone) ? state.user.phone : '',
			email: (state.user.email) ? state.user.email : '',
			discount: (state.discount._embedded.items[0]) ? state.discount._embedded.items[0] : ''
		}
	},
	getOrders(state) {
		return {
			items: state.orders._embedded.items,
			next: (state.orders._links.next) ? state.orders._links.next : null
		}
	},
}