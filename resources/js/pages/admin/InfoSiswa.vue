<template>
    <a-row type="flex" justify="center">
        <ul></ul>
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="Informasi Siswa"
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
                    <a-select
                        v-model:value="filters.kelas_id"
                        style="width: 120px"
                        placeholder="--kelas--"
                        @change="handleChange"
                    >
                        <a-select-option :value="null">Semua</a-select-option>
                        <a-select-option
                            v-for="(kelas, index) in classes"
                            :key="kelas.id"
                            :value="kelas.id"
                            >{{
                                `${kelas.jenjang}  ${kelas.section}`
                            }}</a-select-option
                        >
                    </a-select>
                    <a-input-search
                        v-model:value="filters.search"
                        placeholder="Cari data siswa"
                        @search="readData"
                    />
                    <router-link to="/admin/kelola_akun/new">
                        <a-button type="primary">Tambah</a-button>
                    </router-link>
                </a-space>
                <a-table
                    :columns="columns"
                    :data-source="models"
                    style="margin: 15px"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key == 'kelas'">
                            <span
                                >{{ record.kelas.jenjang }} -
                                {{ record.kelas.section }}</span
                            >
                        </template>
                        <template v-if="column.key == 'is_beasiswa'">
                            <span v-if="record.is_beasiswa"
                                ><close-square-outlined
                            /></span>
                            <span v-else><close-circle-outlined /></span>
                        </template>
                        <template v-if="column.key === 'action'">
                            <span>
                                <a-button
                                    style="margin-left: 3px"
                                    type="danger"
                                    @click="alert(record.user_id, deleteData)"
                                >
                                    <template #icon>
                                        <delete-outlined
                                            :style="{ color: '#ffffff' }"
                                        />
                                    </template>
                                </a-button>
                                <router-link
                                    :to="`/admin/info_siswa/${record.user_id}`"
                                >
                                    <a-button
                                        style="margin-left: 3px"
                                        type="primary"
                                    >
                                        <template #icon>
                                            <eye-outlined
                                                :style="{ color: '#ffffff' }"
                                            />
                                        </template>
                                    </a-button>
                                </router-link>
                            </span>
                        </template>
                    </template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>
</template>
<script>
const columns = [
    {
        title: 'No',
        dataIndex: 'number'
    },
    {
        title: 'Nama Siswa',
        dataIndex: ['user', 'name']
    },
    {
        title: 'Kelas',
        key: 'kelas'
    },
    {
        title: 'NISN',
        dataIndex: ['nisn']
    },
    {
        title: 'Beasiswa',
        key: 'is_beasiswa'
    },
    {
        title: 'Aksi',
        key: 'action'
    }
]
export default {
    data() {
        return {
            columns,

            models: [],
            filters: {
                search: null,
                kelas_id: null
            },
            loading: false,
            classes: []
        }
    },
    mounted() {
        this.readData()
        this.getKelas()
    },
    watch: {
        'filters.kelas_id'() {
            this.readData()
        }
    },
    methods: {
        getKelas() {
            const vm = this
            vm.axios
                .get(vm.url('kelas/read'), { params: { req: 'table' } })
                .then((response) => {
                    vm.classes = response.data.models
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        readData() {
            const vm = this
            vm.loading = true
            const params = {
                req: 'table',
                ...vm.filters
            }

            vm.axios
                .get(vm.url('siswa/read'), { params: params })
                .then((response) => {
                    vm.loading = false
                    response.data.models.forEach((item, index) => {
                        item.number = index + 1
                    })
                    vm.models = response.data.models

                    console.log(vm.models)
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        handleChange(value) {
            this.filters.kelas_id = value
        },
        deleteData(_id) {
            console.log(_id)
            const vm = this
            const params = {
                req: 'delete',
                id: _id
            }
            vm.axios
                .post(vm.url('user/write'), params)
                .then((response) => {
                    vm.$notification.success({
                        message: 'Berhasil',
                        description: response.data.message
                    })
                    vm.readData()
                })
                .catch((e) => (vm.validation = vm.$onAjaxError(e)))
        }
    }
}
</script>
