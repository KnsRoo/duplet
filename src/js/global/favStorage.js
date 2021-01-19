export default class {
	put = (item) => {
		let favorites = this.get()
		favorites.push(item)
		localStorage.setItem("favorites", favorites)
	}

	get = () => {
		return localStorage.getItem("favorites")
	}

	remove = (item) => {
		let favorites = this.get()
		favorites.remove(item)
		localStorage.setItem("favorites", favorites)	
	}

	push = async () => {
		await fetch('/api/user/props', {
			method: 'GET',
			op: 'add',
			path: '/favorites',
			body: JSON.stringify(this.get())
		})
	}
}