import ky from 'ky'

export default {
    async fetchItems({commit}, { link, type }){
        let result = ky.get(link).json()
        (type == 0) ? commit('setNewItems', result) : commit('setPopularItems', result) 
    }
}