import axios from 'axios'
import lodash from 'lodash'
import { Modal } from 'ant-design-vue'

export default {
    data() {
        return {
            axios,
            lodash,
            loggedIn: localStorage.getItem('loggedIn'),
            token: localStorage.getItem('token'),
            validation: {},
            loading: false
        }
    },
    computed: {
        route() {
            return window.location.pathname
        },
        readRoute() {
            return window.location.pathname + '/read'
        },
        writeRoute() {
            return window.location.pathname + '/write'
        }
    },
    methods: {
        url(_url) {
            return window.location.origin + '/api/' + _url
        },
        checkValidation(arr, id = '') {
            const vm = this
            vm.resetValidation()
            for (let key in arr) {
                let el = document.querySelectorAll(
                    '[data-column="' + key + (id ? '_' + id : '') + '"]'
                )
                if (el) {
                    el.forEach((child) => {
                        child.classList.add('ant-form-item-has-error')
                    })
                }
            }
        },
        $onAjaxError(error, id) {
            const vm = this
            let description = ''

            if (
                error.response &&
                error.response.status == 422 &&
                error.response.data.field
            ) {
                vm.openNotification(
                    `Error ${error.response.status}: ${error.response.data.message}`,
                    'error'
                )
                const validation = {}
                const field = error.response.data.field
                if (Array.isArray(field)) {
                    for (let f in field) {
                        validation[field[f]] = true
                    }
                } else {
                    validation[error.response.data.field] = true
                }

                vm.checkValidation(validation, id)
                throw new Error(validation)
            } else if (error.response && error.response.status == 422) {
                const validation = error.response.data.errors
                vm.checkValidation(validation, id)
                vm.openNotification(
                    `Error ${error.response.status}: ${error.response.data.message}`,
                    'error'
                )
                return validation
            } else if (error.response && error.response.status == 404) {
                vm.openNotification(
                    `Error ${error.response.status}: data not found`,
                    'error'
                )
            } else if (error.response && error.response.status == 401) {
                vm.openNotification(
                    `Error ${error.response.status}: Access Denied`,
                    'error'
                )
            } else if (error.response && error.response.data) {
                description = error.response.data
                if (typeof description == 'object') {
                    description = JSON.stringify(error)
                }

                vm.$notification.error({
                    message: 'Error',
                    description
                })
            }
        },
        resetValidation() {
            document
                .querySelectorAll('div.ant-form-item-has-error')
                .forEach((el) => el.classList.remove('ant-form-item-has-error'))

            this.validation = {}
        },
        openNotification(description, type = 'info') {
            let title_msg = null
            let config = { description: description, placement: 'topRight' }

            switch (type) {
                case 'info':
                    config = {
                        ...config,
                        message: 'Informasi' /* class: 'ant-notif-info' */
                    }
                    break
                case 'warning':
                    config = {
                        ...config,
                        message: 'Peringatan' /* class: 'ant-notif-warning' */
                    }
                    break
                case 'success':
                    config = {
                        ...config,
                        message: 'Berhasil' /* class: 'ant-notif-success' */
                    }
                    break
                case 'error':
                    config = {
                        ...config,
                        message:
                            'Terjadi Kesalahan' /* class: 'ant-notif-error' */
                    }
                    break
            }

            this.$notification[type](config)
        },
        alert(id, callback) {
            Modal.confirm({
                title: 'Konfirmasi',
                content: 'Apa anda yakin menghapus data ini?',
                onOk() {
                    callback(id)
                },
                okText: 'Yes',
                cancelText: 'Cancel'
            })
        }
    }
}
