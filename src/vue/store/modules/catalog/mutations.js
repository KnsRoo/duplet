export default {
	setCatalogGroups(state, items) {
		state.end = true
		if (items.length != 0){
			state.catalogGroups = items
			state.end = false
		}
		
	},
	setCatalogProducts(state, {items, next}) {
		state.catalogItems = items
		state.next = next
	},

	addCatalogProducts(state, {items, next}) {
		state.catalogItems = state.catalogItems.concat(items)
		state.next = next
	},
};