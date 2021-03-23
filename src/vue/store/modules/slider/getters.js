export default {
	popularCats(state){
		return Object.keys(state.popularItems)
	},
	noveltyCats(state){
		return Object.keys(state.newItems)
	},
	popular(state){
		if (state.activePopular == 'all'){
			let result = []
			Object.entries(state.popularItems).forEach(([k,v]) => {
				result = result.concat(v)
			})
			return result
		} else {
			return state.popularItems[state.activePopular]
		}
	},
	novelty(state){
		if (state.activeNovelty == 'all'){
			let result = []
			Object.entries(state.newItems).forEach(([k,v]) => {
				result = result.concat(v)
			})
			return result
		} else {
			return state.newItems[state.activeNovelty]
		}
	}
}