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

    async saveUser({dispatch, getters}, changed){
        if (changed.name != ''){
            await dispatch('resetName',changed.name)
        }
        if (changed.phone != ''){
            await dispatch('resetPhone',changed.phone)
        }
        if (changed.email != ''){
            await dispatch('resetEmail',changed.email)
        }
        if (changed.address != ''){
            await dispatch('resetAddress',changed.address)
        }
        if (changed.city != ''){
            await dispatch('resetAddress',changed.city)
        }
        if (changed.dicount != ''){
            await dispatch('resetCardNumber',changed.discount)
        }    
        await dispatch('fetchUser')
    },

    async resetPhone(ctx, phone){
        try {
            const response = await authfetch(`${window.location.origin}/api/user/phone`, {
                method: 'PUT',
                body: JSON.stringify({
                    phone: '7'+phone,
                }),
            })
            if(response.errors) {
                console.log(response.errors)
            }
        }
        catch {
            noty('error', 'Ошибка записи телефона')
        }
    },
    async resetEmail(ctx, email){
        try {
            const response = await authfetch(`${window.location.origin}/api/user/email`, {
                method: 'PUT',
                body: JSON.stringify({
                    email,
                }),
            })
            if(response.errors) {
                console.log(response.errors)
            }
        }
        catch {
            noty('error', 'Ошибка записи email')
        }
    },
    async resetName(ctx, name){
        try {
            const response = await authfetch(`${window.location.origin}/api/user/props`, {
                method: 'PATCH',
                body: JSON.stringify([{
                    op: 'add',
                    path: '/name',
                    value: name,
                }]),
            })
            if(response.errors) {
                console.log(response.errors)
            }
        }
        catch {
            noty('error', 'Ошибка записи Имени')
        }
    },
    async resetAddress(ctx, address){
        try {
            const response = await authfetch(`${window.location.origin}/api/user/props`, {
                method: 'PATCH',
                body: JSON.stringify([{
                    op: 'add',
                    path: '/address',
                    value: address,
                }]),
            })
            if(response.errors) {
                console.log(response.errors)
            }
        }
        catch {
            noty('error', 'Ошибка записи адреса')
        }
    },
    async resetCity(ctx, city){
        try {
            const response = await authfetch(`${window.location.origin}/api/user/props`, {
                method: 'PATCH',
                body: JSON.stringify([{
                    op: 'add',
                    path: '/city',
                    value: city,
                }]),
            })
            if(response.errors) {
                console.log(response.errors)
            }
        }
        catch {
            noty('error', 'Ошибка записи адреса')
        }
    },
    async resetCardNumber(ctx, cardNumber){
        try {
            const response = await authfetch(`${window.location.origin}/api/user/props`, {
                method: 'PATCH',
                body: JSON.stringify([{
                    op: 'add',
                    path: '/discountCard',
                    value: cardNumber,
                }]),
            })
            if(response.errors) {
                console.log(response.errors)
            }
        }
        catch {
            noty('error', 'Ошибка записи номера карты')
        }
    }
}