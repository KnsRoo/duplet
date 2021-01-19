import ky from 'ky'
import authfetch from '../../../../js/components/authfetch'
import noty from "../../../../js/components/noty";

export default {
	async fetchUser({commit, dispatch}){
    	try {
            const response = await authfetch(`${window.location.origin}/api/user`, {
                method: 'GET',
            })

            if(response.errors) {
                console.log(response.errors)
            }
            else {
                const data = await response.json();
                commit('setUser', data)
                await dispatch('fetchProps', data._links.props.href)
                await dispatch('fetchOrders', data._links.orders.href)
                await dispatch('fetchDiscountCards', data._links['discount-cards'].href)
            }
        }
        catch {
            noty("error", "Ошибка получения данных. Код ошибки 2441");
        }
	},
    async fetchProps({commit}, link){
        try {
            const response = await authfetch(link, {
                method: 'GET',
            })

            if(response.errors) {
                console.log(response.errors)
            }
            else {
                const data = await response.json();
                commit('setUserProps', data)
            }
        }
        catch {
            noty("error", "Ошибка получения данных Код ошибки 2442");
        } 
    },
    async fetchOrders({commit}, link){
        try {
            const response = await authfetch(link, {
                method: 'GET',
            })

            if(response.errors) {
                console.log(response.errors)
            }
            else {
                const data = await response.json();
                commit('setUserOrders', data)
            }
        }
        catch {
            noty("error", "Ошибка получения данных Код ошибки 2443");
        } 
    },
    async fetchDiscountCards({commit}, link){
        try {
            const response = await authfetch(link, {
                method: 'GET',
            })

            if(response.errors) {
                console.log(response.errors)
            }
            else {
                const data = await response.json();
                commit('setUserDiscountCards', data)
            }
        }
        catch {
            noty("error", "Ошибка получения данных Код ошибки 2444");
        } 
    },
    async saveUser({dispatch}, info){
        await dispatch('resetEmail',info.email)
        await dispatch('resetName',info.name)
        await dispatch('resetPhone',info.phone)
        await dispatch('resetCardNumber',info.discount)
        await dispatch('fetchUser')
    },
    async resetPhone(ctx, phone){
        try {
            const response = await authfetch('/api/user/phone', {
                method: 'PUT',
                body: JSON.stringify({
                    email,
                }),
            })
            if(response.errors) {
                this.errors.push("Ошибка записи телефона!");
                return false;
            }
            else {
                this.errors = [];
                return true;
            }
        }
        catch {
            this.errors.push("Ошибка записи email!");
            return false;
        }
    },
    async resetEmail(ctx, email){
        try {
            const response = await authfetch('/api/user/email', {
                method: 'PUT',
                body: JSON.stringify({
                    email,
                }),
            })
            if(response.errors) {
                this.errors.push("Ошибка записи email!");
                return false;
            }
            else {
                this.errors = [];
                return true;
            }
        }
        catch {
            this.errors.push("Ошибка записи email!");
            return false;
        }
    },
    async resetName(ctx, name){
        try {
            const response = await authfetch('/api/user/props', {
                method: 'PATCH',
                body: JSON.stringify([{
                    op: 'add',
                    path: '/name',
                    value: name,
                }]),
            })
            if(response.errors) {
                this.errors.push("Ошибка записи имени!");
                return false;
            }
            else {
                this.errors = [];
                return true;
            }
        }
        catch {
            this.errors.push("Ошибка записи имени!");
            return false;
        }
    },
    async resetCardNumber(ctx, cardNumber){
        try {
            const response = await authfetch('/api/user/props', {
                method: 'PATCH',
                body: JSON.stringify([{
                    op: 'add',
                    path: '/discountCard',
                    value: cardNumber,
                }]),
            })
            if(response.errors) {
                this.errors.push("Ошибка записи номера карты 1!");
                return false;
            }
            else {
                this.errors = [];
                return true;
            }
        }
        catch {
            this.errors.push("Ошибка записи номера карты 2!");
            return false;
        }
    }
}