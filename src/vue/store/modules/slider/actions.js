import ky from 'ky'

export default {
    async fetchItems({commit}, { link, type }){
        let result = await ky.get(link).json()
        let products = {}
        for (const val of result._embedded.items){
            let items = await ky.get(`${window.location.origin}/api/pages/pages/${val.id}/props`, {timeout: false}).json()
            items = items._embedded.props["товары"]
            if (items) products[val.title] = items.value
        }
        (type == 0) ? commit('setNewItems', products) : commit('setPopularItems', products) 
    },
    setCategory({commit}, category){
    	commit('setActiveCategory', category)
    }
}