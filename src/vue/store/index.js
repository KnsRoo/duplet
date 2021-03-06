import Vue from "vue";
import Vuex from "vuex";
import catalog from "./modules/catalog"
import cart from "./modules/cart"
import user from "./modules/user"
import orders from "./modules/orders"
import favorites from "./modules/favorites"
import slider from "./modules/slider"

Vue.use(Vuex);
const store = new Vuex.Store({
    modules: {
        catalog,
        cart,
        favorites,
        user,
        orders,
        slider
    },
    state: {},
    actions: {},
    mutations: {},
    getters: {}
})

export default store;
