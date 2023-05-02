require('./bootstrap')
import router from './router'
import 'ant-design-vue/dist/antd.css'
import App from './App.vue'
import { createApp } from 'vue'
import { createStore } from 'vuex'
import VueCookies from 'vue-cookies'
import store from './store/index'

const app = createApp(App)
app.use(store)
app.use(router)
app.use(VueCookies)
app.config.errorHandler = () => null
app.config.warnHandler = () => null

import { useLoader as loadComponent } from './antd-loader'
loadComponent(app)

import { useLoader as loadIcon } from './antd-icon-loader'
loadIcon(app)

import mixins from './mixins'
app.mixin(mixins)

app.mount('#app')
