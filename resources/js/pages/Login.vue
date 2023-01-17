<template>
    <navbar-home>
        <a-row type="flex" justify="center">
            <a-col :xs="22" :sm="20" :lg="18">
                <a-card title="SMP Al Hijrah">
                    Login Page
                    <a-form
                        name="login"
                        :label-col="{ span: 8 }"
                        :wrapper-col="{ span: 16 }"
                        class="login-form"
                        autocomplete="off"
                    >
                        <a-form-item
                            label="Username"
                            name="username"
                            :class="{
                                'ant-form-item-has-error': validation.username
                            }"
                        >
                            <a-input v-model:value="form.username">
                                <template #prefix>
                                    <UserOutlined class="site-form-item-icon" />
                                </template>
                            </a-input>
                        </a-form-item>

                        <a-form-item
                            label="Password"
                            name="password"
                            :class="{
                                'ant-form-item-has-error': validation.password
                            }"
                        >
                            <a-input-password v-model:value="form.password">
                                <template #prefix>
                                    <LockOutlined class="site-form-item-icon" />
                                </template>
                            </a-input-password>
                        </a-form-item>

                        <a-form-item>
                            <a-button
                                :loading="loading"
                                type="primary"
                                class="login-form-button"
                                @click.prevent="login"
                            >
                                Login
                            </a-button>
                        </a-form-item>
                    </a-form></a-card
                ></a-col
            ></a-row
        ></navbar-home
    >
</template>
<script>
import NavbarHome from '../layout/NavbarHome'
export default {
    components: { NavbarHome },
    data() {
        return {
            form: {
                username: null,
                password: null
            },
            loading: false
        }
    },
    mounted() {},
    methods: {
        login() {
            const vm = this
            vm.loading = true
            const params = {
                username: vm.form.username,
                password: vm.form.password
            }

            vm.axios.get(vm.url('sanctum/csrf-cookie')).then((response) => {
                vm.axios
                    .post(vm.url('login'), params)
                    .then((response) => {
                        if (response.data.success) {
                            console.log(response.data.token)
                            // Menyimpan data status login ke dalam localStorage
                            localStorage.setItem('loggedIn', 'true')

                            // Menyimpan  token ke dalam localStorage
                            localStorage.setItem('token', response.data.token)

                            console.log(
                                response.data.user.roles[0].display_name
                            )
                            window.location.href = `/${response.data.user.roles[0].display_name}`
                        }
                    })
                    .catch((error) => (vm.validation = vm.$onAjaxError(error)))
            })

            vm.loading = false
        }
    }
}
</script>
