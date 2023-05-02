import { createStore } from 'vuex'
import { notification } from 'ant-design-vue'

const store = createStore({
    state() {
        return {
            userData: null,
            notification
        }
    },
    mutations: {
        setUserData(state, data) {
            state.userData = data
        }
    },
    getters: {
        getUserData(state) {
            return state.userData
        }
    }
})

export default store
