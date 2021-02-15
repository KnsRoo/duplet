export default class Store{
	constructor(name){
		this.name = name
		this.store = localStorage.getItem(name).split(',')
		console.log(this.store)
		if (!this.store){
			this.store = []
		}
	}

	push = (item) => {
		if (this.store){
			if (!this.store.includes(item)){
				this.store.unshift(item)
			}
		} else {
			this.store.unshift(item)
		}
		if (this.store && this.store.length > 15){
			this.store.pop()
		}
		localStorage.setItem(this.name, this.store)
	}

	isEmpty(){
		return this.store.length == 0
	}

	get = () => {
		return this.store
	}
}