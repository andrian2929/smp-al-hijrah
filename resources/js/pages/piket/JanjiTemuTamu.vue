<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <h1>Temu</h1>
            <a-card>
                <a-button
                    type="primary"
                    @click="modalvisible.formJanjiTemu = true"
                    >Tambah</a-button
                >
                <div style="overflow: auto">
                    <a-table
                        :data-source="janjiTemuTamuDataSources"
                        :columns="janjiTemuTamuColumns"
                        style="margin-top: 10px"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'aksi'">
                                <a-space>
                                    <a-button
                                        type="primary"
                                        @click="
                                            () => {
                                                editMode.formJanjiTemu = true
                                                showJanjiTemu(record.id)
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
                                            alert(record.id, deleteJanjiTemu)
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

    <a-modal title="Janji Temu" v-model:visible="modalvisible.formJanjiTemu">
        <template #footer></template>
        <a-form
            :model="formJanjiTemu"
            layout="vertical"
            @finish="editMode.formJanjiTemu ? editJanjiTemu() : saveJanjiTemu()"
        >
            <a-form-item
                label="Tujuan"
                name="tujuan"
                :rules="[
                    {
                        required: true,
                        message: 'Tujuan tidak boleh kosong'
                    }
                ]"
            >
                <a-input
                    v-model:value="formJanjiTemu.tujuan"
                    placeholder="Masukkan tujuan"
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
                    v-model:value="formJanjiTemu.tanggal"
                    value-format="YYYY-MM-DD"
                    style="width: 100%"
                    placeholder="Pilih tanggal janji temu"
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
                    v-model:value="formJanjiTemu.jam"
                    value-format="HH:mm"
                    style="width: 100%"
                    format="HH:mm"
                    placeholder="Pilih waktu janji temu"
                />
            </a-form-item>
            <a-form-item
                label="Nama Tamu"
                name="namaTamu"
                :rules="[
                    {
                        required: true,
                        message: 'Nama tamu tidak boleh kosong'
                    }
                ]"
            >
                <a-input
                    v-model:value="formJanjiTemu.namaTamu"
                    placeholder="Masukkan nama tamu"
                />
            </a-form-item>
            <a-form-item label="Keterangan" name="keterangan">
                <a-textarea
                    v-model:value="formJanjiTemu.keterangan"
                    placeholder="Masukkan keterangan"
                />
            </a-form-item>

            <a-button type="primary" htmlType="submit"> Simpan </a-button>
        </a-form>
    </a-modal>
</template>
<script>
const janjiTemuTamuColumns = [
    {
        title: 'No',
        dataIndex: 'no'
    },
    {
        title: 'Nama Tamu',
        dataIndex: 'namaTamu'
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
        title: 'Tujuan',
        dataIndex: 'tujuan'
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

const janjiTemuTamuDataSources = [
    {
        no: '1',
        tujuan: 'Budi',
        tanggal: '12 November 2022',
        jam: '12:00',
        namaTamu: 'Budi',
        keterangan: 'Ingin berjumpa dengan kepala sekolah'
    },
    {
        no: '2',
        tujuan: 'Budi',
        tanggal: '12 November 2022',
        jam: '12:00',
        namaTamu: 'Budi',
        keterangan: 'Ingin berjumpa dengan kepala sekolah'
    }
]

import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue'

export default {
    data() {
        return {
            janjiTemuTamuDataSources,
            janjiTemuTamuColumns,
            modalvisible: {
                formJanjiTemu: false
            },
            editMode: {
                formJanjiTemu: false
            },
            formJanjiTemu: {}
        }
    },
    mounted() {
        this.getJanjiTemuData()
    },
    methods: {
        saveJanjiTemu() {
            const vm = this
            vm.editMode.formJanjiTemu = false
            vm.formJanjiTemu.nama_tamu = vm.formJanjiTemu.namaTamu
            delete vm.formJanjiTemu.namaTamu

            vm.axios
                .post(vm.url('piket/tamu/janji-temu'), vm.formJanjiTemu)
                .then((res) => {
                    if (res.status === 200) {
                        vm.$notification.success({
                            message: 'Berhasil',
                            description: 'Data berhasil disimpan'
                        })
                        vm.getJanjiTemuData()
                        vm.modalvisible.formJanjiTemu = false
                        vm.formJanjiTemu = {}
                    }
                })
                .catch((err) => {
                    if (err.response) {
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                    }
                })
        },
        getJanjiTemuData() {
            const vm = this
            vm.axios
                .get(vm.url('piket/tamu/janji-temu'))
                .then((res) => {
                    if (res.status === 200) {
                        vm.janjiTemuTamuDataSources = res.data.data.map(
                            (item, index) => {
                                return {
                                    no: index + 1,
                                    id: item.id,
                                    tujuan: item.tujuan,
                                    tanggal: item.tanggal,
                                    jam: item.jam.substring(0, 5),
                                    namaTamu: item.nama_tamu,
                                    keterangan: item.keterangan
                                }
                            }
                        )
                    }
                })
                .catch((err) => {
                    if (err.response) {
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                    }
                })
        },
        showJanjiTemu(janjiTemuId) {
            const vm = this
            vm.axios
                .get(vm.url('piket/tamu/janji-temu/' + janjiTemuId))
                .then((res) => {
                    if (res.status === 200) {
                        vm.formJanjiTemu = res.data.data
                        vm.formJanjiTemu.namaTamu = res.data.data.nama_tamu
                        delete vm.formJanjiTemu.nama_tamu
                        vm.modalvisible.formJanjiTemu = true
                        vm.editMode.formJanjiTemu = true
                    }
                })
                .catch((err) => {
                    if (err.response) {
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                    }
                })
        },
        editJanjiTemu() {
            const vm = this

            vm.formJanjiTemu.nama_tamu = vm.formJanjiTemu.namaTamu
            vm.formJanjiTemu.jam = vm.formJanjiTemu.jam.substring(0, 5)
            delete vm.formJanjiTemu.namaTamu

            vm.axios
                .put(
                    vm.url('piket/tamu/janji-temu/' + vm.formJanjiTemu.id),
                    vm.formJanjiTemu
                )
                .then((res) => {
                    if (res.status === 200) {
                        vm.$notification.success({
                            message: 'Berhasil',
                            description: 'Data berhasil disimpan'
                        })
                        vm.getJanjiTemuData()
                        vm.modalvisible.formJanjiTemu = false
                        vm.editMode.formJanjiTemu = false
                        vm.formJanjiTemu = {}
                    }
                })
                .catch((err) => {
                    if (err.response) {
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                    }
                })
        },
        deleteJanjiTemu(janjiTemuId) {
            const vm = this
            vm.axios
                .delete(vm.url('piket/tamu/janji-temu/' + janjiTemuId))
                .then((res) => {
                    if (res.status === 200) {
                        vm.$notification.success({
                            message: 'Berhasil',
                            description: 'Data berhasil dihapus'
                        })
                        vm.getJanjiTemuData()
                    }
                })
                .catch((err) => {
                    if (err.response) {
                        vm.$notification.error({
                            message: 'Terjadi kesalahan',
                            description: err.response.data.message
                        })
                    }
                })
        }
    }
}
</script>
