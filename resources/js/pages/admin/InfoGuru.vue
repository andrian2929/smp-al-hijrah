<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="Informasi Guru"
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
                    :columns="columns"
                    :data-source="models"
                    style="margin: 15px"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'nama'">
                            <a>
                                {{ record.nama }}
                            </a>
                        </template>
                        <template v-if="column.key === 'wali_kelas'">
                            <span v-if="record.wali_kelas"
                                >{{ record.wali_kelas.jenjang }} -
                                {{ record.wali_kelas.section }}</span
                            >
                        </template>
                        <template v-else-if="column.key === 'action'">
                            <span>
                                <a-button
                                    style="margin-left: 3px"
                                    type="danger"
                                    @click="alert(record.id, deleteData)"
                                >
                                    <template #icon>
                                        <delete-outlined
                                            :style="{ color: '#ffffff' }"
                                        />
                                    </template>
                                </a-button>
                                <router-link
                                    :to="`/admin/info_guru/${record.id}`"
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
        title: 'ID',
        dataIndex: 'id'
    },
    {
        title: 'Nama Guru',
        dataIndex: 'name'
    },
    {
        title: 'Wali Kelas',
        key: 'wali_kelas'
    },
    {
        title: 'NIP',
        dataIndex: 'no_induk'
    },
    {
        title: '',
        key: 'action'
    }
]
const data = [
    {
        key: '1',
        nama_guru: 'Muhammad Farhan Syahreza',
        wali_kelas: 'VII A',
        nip: 1908729384758492
    },
    {
        key: '2',
        nama_guru: 'Muhammad Farhan Syahreza',
        wali_kelas: 'VII A',
        nip: 1908729384758492
    },
    {
        key: '3',
        nama_guru: 'Muhammad Farhan Syahreza',
        wali_kelas: 'VII A',
        nip: 1908729384758492
    }
]
export default {
    data() {
        return {
            data,
            models: [],
            columns,
            filters: {
                search: null
            },
            loading: false
        }
    },
    mounted() {
        this.readData()
    },
    methods: {
        readData() {
            const vm = this
            vm.loading = true
            const params = {
                req: 'table',
                ...vm.filters
            }
            vm.axios
                .get(vm.url('guru/read'), { params: params })
                .then((response) => {
                    console.log(response)
                    vm.loading = false
                    vm.models = response.data.models
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
                    vm.openNotification('data berhasil terhapus', 'success')
                    vm.readData()
                })
                .catch((e) => (vm.validation = vm.$onAjaxError(e)))
        }
    }
}
</script>
