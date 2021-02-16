export default {
	catalogGroups(state) {
		return state.catalogGroups
	},
	catalogItems(state) {
		return state.catalogItems
	},
	catalogFilters(state) {
		return state.filters
	},
	getFilterGroupId(state) {
		return state.filterGroupId;
	},
	getGroupId(state) {
		return state.groupId;
	},
	isNext(state) {
		return state.next
	},
	isEnd(state) {
		return state.end
	},
	current(state){
		return state.current
	},
	product(state){
		console.log("product getter",state.product)
		return state.product
	}
};