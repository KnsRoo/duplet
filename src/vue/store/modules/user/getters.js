export default {
	getUser(state) {
		return {
			name: (state.properties.name) ? state.properties.name : '',
			phone: (state.user.phone) ? state.user.phone : '',
			email: (state.user.email) ? state.user.email : '',
			address: (state.properties.address) ? state.properties.address : '',
			city: (state.properties.city) ? state.properties.city : '',
			discount: (state.properties.discountCard) ? state.properties.discountCard : ''
		}
	},
	orders(state) {
		return {
			items: state.orders._embedded.items,
			next: (state.orders._links.next) ? state.orders._links.next : null
		}
	},
}