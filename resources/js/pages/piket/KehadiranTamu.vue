<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <h1>Kehadiran Tamu</h1>
            <a-card>
                <a-button
                    type="primary"
                    @click="
                        () => {
                            modalVisible.formKehadiranTamu = true
                        }
                    "
                >
                    Tambah</a-button
                >
                <div style="overflow: auto">
                    <a-table
                        :data-source="kehadiranTamuDataSources"
                        :columns="kehadiranTamuColumns"
                        style="margin-top: 10px"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'aksi'">
                                <a-space>
                                    <a-button
                                        type="primary"
                                        @click="
                                            () => {
                                                editMode.formKehadiranTamu = true
                                                showKehadiranTamu(record.id)
                                            }
                                        "
                                    >
                                        <template #icon
                                            ><EditOutlined
                                        /></template>
                                    </a-button>
                                    <a-button
                                        type="danger"
                                        @click="
                                            alert(
                                                record.id,
                                                deleteKehadiranTamu
                                            )
                                        "
                                    >
                                        <template #icon
                                            ><DeleteOutlined
                                        /></template>
                                    </a-button>
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-card>
        </a-col>
    </a-row>

    <a-modal
        title="Kehadiran Tamu"
        v-model:visible="modalVisible.formKehadiranTamu"
    >
        <template #footer> </template>
        <a-form
            :model="formKehadiranTamu"
            layout="vertical"
            @finish="
                editMode.formKehadiranTamu
                    ? editKehadiranTamu()
                    : saveKehadiranTamu()
            "
        >
            <a-form-item
                label="Nama"
                name="nama"
                :rules="[
                    {
                        required: true,
                        message: 'Nama tidak boleh kosong'
                    }
                ]"
            >
                <a-input
                    v-model:value="formKehadiranTamu.nama"
                    placeholder="Masukkan nama"
                />
            </a-form-item>
            <a-form-item
                label="Tanggal"
                name="tanggal"
                :rules="[
                    {
                        required: true,
                        message: 'Tanggal tidak boleh kosong'
                    }
                ]"
            >
                <a-date-picker
                    v-model:value="formKehadiranTamu.tanggal"
                    value-format="YYYY-MM-DD"
                    placeholder="PIlih tanggal"
                    style="width: 100%"
                />
            </a-form-item>
            <a-form-item
                label="Jam"
                name="jam"
                :rules="[
                    {
                        required: true,
                        message: 'Jam tidak boleh kosong'
                    }
                ]"
            >
                <a-time-picker
                    v-model:value="formKehadiranTamu.jam"
                    format="HH:mm"
                    value-format="HH:mm"
                    placeholder="Pilih jam"
                    style="width: 100%"
                />
            </a-form-item>
            <a-form-item label="Keterangan">
                <a-textarea
                    v-model:value="formKehadiranTamu.keterangan"
                    :rows="4"
                    placeholder="Masukkan keterangan"
                />
            </a-form-item>
            <a-form-item>
                <a-button type="primary" htmlType="submit"> Simpan </a-button>
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script>
const kehadiranTamuColumns = [
    {
        title: 'No',
        dataIndex: 'no'
    },
    {
        title: 'Nama',
        dataIndex: 'nama'
    },
    {
        title: 'Tanggal',
        dataIndex: 'tanggal'
    },
    {
        title: 'Jam',
        dataIndex: 'jam'
    },
    {
        title: 'Keterangan',
        dataIndex: 'keterangan'
    },
    {
        title: 'Aksi',
        dataIndex: 'aksi'
    }
]

import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue'

export default {
    data() {
        return {
            kehadiranTamuDataSources: [],
            kehadiranTamuColumns,
            modalVisible: {
                formKehadiranTamu: false
            },
            formKehadiranTamu: {},
            editMode: {
                formKehadiranTamu: false
            }
        }
    },
    mounted() {
        this.getKehadiranTamuData()
    },
    methods: {
        saveKehadiranTamu() {
            const vm = this
            vm.editMode.formKehadiranTamu = false
            vm.axios
                .post(vm.url('piket/tamu/kehadiran'), vm.formKehadiranTamu)
                .then((res) => {
                    if (res.status == 200) {
                        vm.formKehadiranTamu = {}
                        vm.modalVisible.formKehadiranTamu = false
                        vm.$notification.success({
                            message: 'Berhasil',
                            description: 'Data berhasil disimpan'
                        })

                        vm.getKehadiranTamuData()
                    }
                })
                .catch((err) => {
                    if (err.response)
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                })
        },
        showKehadiranTamu(kehadiranTamuId) {
            const vm = this
            vm.formKehadiranTamu = {}

            vm.axios
                .get(vm.url('piket/tamu/kehadiran/' + kehadiranTamuId))
                .then((res) => {
                    if (res.status == 200) {
                        vm.formKehadiranTamu = res.data.data
                        vm.formKehadiranTamu.jam =
                            vm.formKehadiranTamu.jam.substring(0, 5)
                        vm.modalVisible.formKehadiranTamu = true
                    }
                })
                .catch((err) => {
                    if (err.response)
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                })
        },
        editKehadiranTamu() {
            const vm = this
            vm.axios
                .put(
                    vm.url('piket/tamu/kehadiran/' + vm.formKehadiranTamu.id),
                    vm.formKehadiranTamu
                )
                .then((res) => {
                    if (res.status == 200) {
                        vm.$notification.success({
                            message: 'Berhasil',
                            description: 'Data berhasil diubah'
                        })
                        vm.formKehadiranTamu = {}
                        vm.modalVisible.formKehadiranTamu = false
                        vm.editMode.formKehadiranTamu = false
                        vm.getKehadiranTamuData()
                    }
                })
                .catch((err) => {
                    if (err.response)
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                })
        },
        getKehadiranTamuData() {
            const vm = this
            vm.axios
                .get(vm.url('piket/tamu/kehadiran'))
                .then((res) => {
                    if (res.status == 200) {
                        vm.kehadiranTamuDataSources = res.data
                        vm.kehadiranTamuDataSources.forEach((item, index) => {
                            item.jam = item.jam.substring(0, 5)
                            item.no = index + 1
                        })
                    }
                })
                .catch((err) => {
                    if (err.response)
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                })
        },
        deleteKehadiranTamu(kehadiranTamuId) {
            const vm = this
            vm.axios
                .delete(vm.url('piket/tamu/kehadiran/' + kehadiranTamuId))
                .then((res) => {
                    if (res.status === 200) {
                        vm.$notification.success({
                            message: 'Berhasil',
                            description: 'Data berhasil dihapus'
                        })
                        vm.getKehadiranTamuData()
                    }
                })
                .catch((err) => {})
        }
    }
}
</script>
