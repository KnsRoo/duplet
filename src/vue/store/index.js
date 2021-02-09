import Vue from "vue";
import Vuex from "vuex";
import catalog from "./modules/catalog"
import cart from "./modules/cart"
import user from "./modules/user"
import orders from "./modules/orders"
import favorites from "./modules/favorites"

Vue.use(Vuex);
const store = new Vuex.Store({
    modules: {
        catalog,
        cart,
        favorites,
        user,
        orders
    },
    state: {},
    actions: {},
    mutations: {},
    getters: {}
})

export default store;
