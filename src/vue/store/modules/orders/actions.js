import ky from 'ky'
import authfetch from '../../../../js/components/authfetch'
import noty from "../../../../js/components/noty";

export default {
	async appendOrder(ctx, order){
		let result = await ky.post(`${window.location.origin}/api/orders`, { json: order })
	},
	async fetchOrders({commit}, type){
    	try {
            const response = await authfetch(`${window.location.origin}/api/user/orders?type=${type}`, {
                method: 'GET',
            })

            if(response.errors) {
                console.warn(response.errors)
            }
            else {
                const result = await response.json();
                commit("setOrders", result)
            }
        }
        catch {
            noty("error", "Ошибка получения данных. Код ошибки 5441");
        }
	},
	async fetchNextOrders({getters, commit}){
    	try {
            const response = await authfetch(`${getters.orders.next}`, {
                method: 'GET',
            })

            if(response.errors) {
                console.warn(response.errors)
            }
            else {
                const result = await response.json();
                commit("addOrders", result)	
            }
        }
        catch {
            noty("error", "Ошибка получения данных. Код ошибки 5442");
        }
	}
}