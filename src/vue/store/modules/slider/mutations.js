export default {
	setNewItems(state, data) {
		state.newItems = data
	},
	setPopularItems(state, data) {
		state.popularItems = data
	},
	setActiveCategory(state, {name, type}){
		(type == 0) ? state.activeNovelty = name : state.activePopular = name 
	}
}