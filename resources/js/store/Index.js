import Vue from 'vue';
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {

    },
    mutations: {

    },
    actions: {
        getCustomers: async (state, getters) => {
            try {
                return axios.get('/api/customer-list')
            } catch (e) {
                toast.error(e.response.message)
            }
        },
        getContracts: async (state, getters) => {
            try {
                return axios.get("/api/contract")
            } catch (e) {
                toast.error(e.response.message)
            }
        },
        getReceipts: async (state, getters) => {
            try {
                return axios.get('/api/receipt')
            } catch (e) {
                toast.error(e.response.message)
            }
        }
    }
})

export default store;