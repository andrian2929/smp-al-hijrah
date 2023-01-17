require('./bootstrap')
import router from './router'
import 'ant-design-vue/dist/antd.css'
import App from './App.vue'
import { createApp } from 'vue'
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
    },
    
})

const app = createApp(App)
app.use(store)
app.use(router)
app.config.errorHandler = () => null
app.config.warnHandler = () => null

import { useLoader as loadComponent } from './antd-loader'
loadComponent(app)

import { useLoader as loadIcon } from './antd-icon-loader'
loadIcon(app)

import mixins from './mixins'
app.mixin(mixins)

app.mount('#app')
