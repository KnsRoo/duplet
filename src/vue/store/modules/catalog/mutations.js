export default {
	setCatalogGroups(state, items) {
		state.catalogGroups = items
		
	},
	setCatalogProducts(state, {items, next}) {
		state.catalogItems = items
		state.next = next
	},

	addCatalogProducts(state, {items, next}) {
		state.catalogItems = state.catalogItems.concat(items)
		console.log(state.catalogItems)
		state.next = next
	},
};