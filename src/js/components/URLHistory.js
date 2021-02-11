export default class URLHistory{
	constructor(){
		this.base = window.location.protocol + "//" + window.location.host + window.location.pathname
	}

	getCurrentParams = () => {
		let url = new URL(window.location.href)
		return new URLSearchParams(url.search)	
	}

	toString = (params) => {
		let string = params.toString()
		return (string != '') ? '?'+string : string
	}

    set = (params) => {
		let currentParams = this.getCurrentParams()
		let extendParams = new URLSearchParams(params)
		for (let [key, value] of extendParams.entries()){
			currentParams.set(key, value)
		}
		return this.toString(currentParams)
	}

	unset = (param) => {
		let currentParams = this.getCurrentParams()
		currentParams.delete(param)
		return this.toString(currentParams)
	}

	add = (key,value) => {
		let refresh = this.base + this.set('?'+key+'='+value)
		window.history.pushState({ path: refresh }, '', refresh);
	}

	remove = (key) => {
		let refresh = this.base + this.unset(key)
		window.history.pushState({ path: refresh }, '', refresh);
	}

	clear = () => {
		let refresh = this.base
		window.history.pushState({ path: refresh }, '', refresh);
	}
}