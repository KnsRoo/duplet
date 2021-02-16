export default {
	favorites(state) {
		return state.items
	},
	count(state) {
		return state.items.length
	}
}