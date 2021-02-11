import ky from 'ky'
import authfetch from '../../../../js/components/authfetch'
import noty from "../../../../js/components/noty";

export default {
	async fetchItems({commit}){
        try {
            const response = await authfetch(`${window.location.origin}/api/favorites`, {
                method: 'GET',
            })  

            let data = await response.json()

            if(data.errors) {
                if (data.errors[0].message == "authorization failed"){
                    window.ModalLogin.toggle()
                } else {
                    throw new Exception(data.errors[0].message)
                }
            } else {
                commit("setItems", data)
            } 

        } catch (e){
            if (typeof e.message != "string"){
                noty("error", "Неизвестная ошибка. Код ошибки 4441");
            }
        }
	},
    async removeItem({dispatch}, itemId){
        try {
            const response = await authfetch(`${window.location.origin}/api/favorites/${itemId}`, {
                method: 'DELETE',
            })  

            if(response.errors) {
                throw new Exception(response.errors[0].message)
            } else {
                await dispatch('fetchItems')
            }     
        } catch (e){
            if (typeof e.message == "string"){
                noty("error", e.message);
            } else {
                noty("error", "Неизвестная ошибка. Код ошибки 4441");
            }
        }
    },
	async appendItem({dispatch}, itemId){
        try {
            const response = await authfetch(`${window.location.origin}/api/favorites`, {
                method: 'POST',
                body: JSON.stringify({'id' : itemId})
            })

            if(response.errors) {
                if (response.errors[0].message == "authorization failed"){
                	window.ModalLogin.toggle()
                } else {
                	throw new Exception(response.errors[0])
                }
            }
            else {
                await dispatch('fetchItems')
                return true
            }
        }
        catch (e){
        	if (typeof e.message == "string"){
        		noty("error", e.message);
        	} else {
				noty("error", "Неизвестная ошибка. Код ошибки 4441");
        	}
            return false
        } 
	},
}