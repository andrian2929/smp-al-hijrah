<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="TATA KELOLA AKUN"
                style="width: 100%"
            >
                <a-space
                    direction="horizontal"
                    style="
                        display: flex;
                        justify-content: flex-end;
                        margin-bottom: 20px;
                    "
                >
                    <a-input-search
                        v-model:value="filters.search"
                        placeholder="cari data"
                        @search="readData"
                    />
                    <router-link to="/admin/kelola_akun/new">
                        <a-button>Tambah</a-button>
                    </router-link>
                </a-space>
                <a-table
                    :columns="[
                        { title: 'ID', dataIndex: 'id' },
                        { title: 'Nama', dataIndex: 'name' },
                        { title: 'Nomor Induk', dataIndex: 'no_induk' },
                        { title: 'Jabatan', key: 'jabatan' },
                        { title: 'Action', key: 'operation' }
                    ]"
                    :data-source="models.data"
                    :pagination="pagination"
                    @change="changePage"
                    size="middle"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key == 'operation'">
                            <a-button
                                warning
                                title="edit"
                                @click="readDataSingle(record.id)"
                            >
                                <template #icon><form-outlined /></template>
                            </a-button>
                            <a-button
                                danger
                                @click="alert(record.id, deleteData)"
                            >
                                <template #icon><delete-outlined /></template>
                            </a-button>
                        </template>
                        <template v-if="column.key == 'jabatan'">
                            {{ record.roles[0].name }}
                        </template>
                    </template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>

    <a-modal v-model:visible="modalEdit" title="Edit User" @ok="editData">
        <a-form
            :label-col="{ span: 6 }"
            :wrapper-col="{ span: 18 }"
            autocomplete="off"
        >
            <a-form-item label="Name" data-column="name" required>
                <a-input
                    v-model:value="form.name"
                    :class="{ 'ant-form-item-has-error': validation.name }"
                    placeholder="name..."
                />
            </a-form-item>
            <a-form-item label="Username" data-column="username" required>
                <a-input
                    v-model:value="form.username"
                    :class="{ 'ant-form-item-has-error': validation.username }"
                    placeholder="username..."
                />
            </a-form-item>
            <a-form-item
                label="Password"
                :class="{ 'ant-form-item-has-error': validation.password }"
                data-column="password"
                required
            >
                <a-input-password
                    v-model:value="form.password"
                    placeholder="input password..."
                />
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script>
export default {
    data() {
        return {
            models: {
                data: [],
                total: null,
                current_page: null,
                per_page: null
            },
            filters: {
                search: null,
                user_type: null,
                per_page: 10
            },
            form: {
                id: null,
                name: null,
                username: null,
                password: null
            },
            modalEdit: false
        }
    },
    mounted() {
        this.readData()
    },
    computed: {
        pagination() {
            const pagination = {
                total: this.models.total,
                current: this.models.current_page,
                pageSize: this.models.per_page
            }
            return pagination
        }
    },
    methods: {
        readData() {
            const vm = this
            vm.loading = true
            const params = {
                req: 'table',
                page: vm.models.current_page,
                ...vm.filters
            }
            vm.axios
                .get(vm.url('user/read'), { params: params })
                .then((response) => {
                    vm.models = response.data.models
                    console.log('andrian')
                    console.log(response.data.models)
                    vm.loading = false
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        readDataSingle(_id) {
            const vm = this
            const params = {
                req: 'single',
                id: _id
            }
            vm.axios
                .get(vm.url('user/read'), { params: params })
                .then((response) => {
                    vm.form = response.data.models
                    vm.form.password = null
                    vm.modalEdit = true
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        deleteData(_id) {
            const vm = this
            const params = {
                req: 'delete',
                id: _id
            }
            vm.axios
                .post(vm.url('user/write'), params)
                .then(() => {
                    vm.readData()
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        editData() {
            const vm = this
            const params = {
                req: 'edit',
                ...vm.form
            }
            vm.axios
                .post(vm.url('user/write'), params)
                .then(() => {
                    vm.readData()
                    vm.validation = {}
                    vm.modalEdit = false
                })
                .catch((e) => (vm.validation = vm.$onAjaxError(e)))
        },
        changePage(page, filters, sorter) {
            this.models.current_page = page.current
            this.models.total = page.total
            this.models.pageSize = page.pageSize
            this.readData()
        }
    }
}
</script>
