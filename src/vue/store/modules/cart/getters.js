export default {
	getItems(state) {
		return state.items
	},

	getCartStat(state){
		let sumTotal = 0;
		let sumReal = 0
		state.items.forEach(val => {	
			let price = (val.discount_price) ? parseFloat(val.discount_price) : parseFloat(val.price)
			sumTotal+=parseInt(val.count)*parseFloat(price)
			sumReal+=parseInt(val.count)*parseFloat(val.price)
		});
		let countTotal = state.items.length
		let discount = sumReal - sumTotal
		return { sumTotal, countTotal, sumReal, discount } 
	},

	reservedItems(state){
		return state.items.filter(val => val.status === 'reserved');	
	},

	readyItems(state){
		return state.items.filter(val => val.status === 'ready');	
	}
}