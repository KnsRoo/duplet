export default {
	getItems(state) {
		return state.items
	},

	getCartStat(state){
		let sumTotal = 0;
		state.items.forEach(val => {
			sumTotal+=parseInt(val.count)*parseFloat(val.price)
		});
		let countTotal = state.items.length
		return { sumTotal, countTotal } 
	}
}