export default {
	setItems(state, result) {
		console.log(result)
		state.items = result._embedded.items

	}
}