<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <h1>Jurnal Siswa</h1>
            <a-card>
                <a-button
                    type="primary"
                    @click="
                        () => {
                            modalVisible.jurnalSiswa = true
                            formJurnalSiswa = {}
                        }
                    "
                    >Tambah</a-button
                >
                <div style="overflow: auto">
                    <a-table
                        :data-source="jurnalSiswaDataSources"
                        :columns="jurnalSiswaColumns"
                        style="margin-top: 10px"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'aksi'">
                                <a-space>
                                    <a-button
                                        type="primary"
                                        @click="showJurnalSiswa(record.id)"
                                    >
                                        <template #icon
                                            ><EditOutlined
                                        /></template>
                                    </a-button>
                                    <a-button
                                        type="danger"
                                        @click="
                                            alert(record.id, deleteJurnalSiswa)
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

    <a-modal v-model:visible="modalVisible.jurnalSiswa" title="Jurnal Siswa">
        <template #footer></template>
        <a-form
            :model="formJurnalSiswa"
            layout="vertical"
            @finish="editMode ? editJurnalSiswa() : saveJurnalSiswa()"
        >
            <a-form-item
                label="Kelas"
                name="kelas"
                :rules="[{ required: true, message: 'Kelas harus diisi' }]"
            >
                <a-select
                    v-model:value="formJurnalSiswa.kelas"
                    placeholder="Pilih kelas"
                    @change="getStudentDatabyKelasId"
                >
                    <a-select-option
                        v-for="kelas in classes"
                        :key="kelas.id"
                        :value="kelas.id"
                    >
                        {{ kelas.jenjang }} - {{ kelas.section }}
                    </a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item
                label="Nama"
                name="nama"
                :rules="[{ required: true, message: 'Nama harus diisi' }]"
            >
                <a-select
                    v-model:value="formJurnalSiswa.nama"
                    placeholder="Pilih nama"
                >
                    <a-select-option
                        v-for="student in studentData"
                        :key="student.user_id"
                        :value="student.user_id"
                    >
                        {{ student.name }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item
                label="Tanggal"
                name="tanggal"
                :rules="[{ required: true, message: 'Tanggal harus diisi' }]"
            >
                <a-date-picker
                    value-format="YYYY-MM-DD"
                    v-model:value="formJurnalSiswa.tanggal"
                    style="width: 100%"
                    @change="getKehadiranSiswaData"
                />
            </a-form-item>

            <a-form-item
                label="Kehadiran"
                name="kehadiran"
                :rules="[
                    {
                        required: true,
                        message: 'Pilih kehadiran terlebih dahulu'
                    }
                ]"
            >
                <a-radio-group v-model:value="formJurnalSiswa.kehadiran">
                    <a-radio value="hadir">Hadir</a-radio>
                    <a-radio value="absen">Absen</a-radio>
                    <a-radio value="telat">Telat</a-radio>
                    <a-radio value="izin">Izin</a-radio>
                </a-radio-group>
            </a-form-item>

            <a-form-item
                label="Jam"
                name="jam"
                :rules="[
                    {
                        required: true,
                        message: 'Jam harus diisi'
                    }
                ]"
            >
                <a-time-picker
                    v-model:value="formJurnalSiswa.jam"
                    format="HH:mm"
                    value-format="HH:mm"
                    placeholder="Masukkan jam"
                    style="width: 100%"
                />
            </a-form-item>

            <a-form-item>
                <a-button type="primary" html-type="submit"> Simpan </a-button>
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script>
const jurnalSiswaDataSources = [
    {
        no: '1',
        nama: 'Budi',
        kehadiran: 'Hadir',
        tanggal: '12 November 2022',
        jam: '12:00',
        keterangan: 'Terlambat'
    },
    {
        no: '2',
        nama: 'Budi',
        kehadiran: 'Hadir',
        tanggal: '12 November 2022',
        jam: '12:00',
        keterangan: 'Terlambat'
    }
]

const jurnalSiswaColumns = [
    {
        title: 'No',
        dataIndex: 'no'
    },
    {
        title: 'Nama',
        dataIndex: 'nama'
    },
    {
        title: 'Kehadiran',
        dataIndex: 'kehadiran'
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
import moment from 'moment'
import 'moment/locale/id'

export default {
    data() {
        return {
            jurnalSiswaDataSources,
            jurnalSiswaColumns,
            formCariSiswa: {},
            modalVisible: {
                jurnalSiswa: false
            },
            editMode: {
                jurnalSiswa: false
            },
            formJurnalSiswa: {},
            classes: [],
            studentData: []
        }
    },
    mounted() {
        this.getKelasData()
        this.getJurnalSiswa()
    },
    methods: {
        saveJurnalSiswa() {
            const vm = this
            const data = {
                user_id: vm.formJurnalSiswa.nama,
                kelas_id: vm.formJurnalSiswa.kelas,
                kehadiran: vm.formJurnalSiswa.kehadiran,
                tanggal: vm.formJurnalSiswa.tanggal,
                jam: vm.formJurnalSiswa.jam
            }

            vm.axios
                .post(vm.url('piket/jurnal/siswa'), data)
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: res.data.message
                    })
                    this.getJurnalSiswa()
                    vm.modalVisible.jurnalSiswa = false
                    vm.formJurnalSiswa = {}
                })
                .catch((err) => {})
        },
        getKelasData() {
            const vm = this
            const params = {
                req: 'all'
            }
            vm.axios
                .get(vm.url('kelas/read'), { params })
                .then((response) => {
                    vm.loading = false
                    vm.classes = response.data.models
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        getStudentDatabyKelasId(kelasId) {
            const vm = this
            delete vm.formJurnalSiswa.nama
            delete vm.formJurnalSiswa.kehadiran
            delete vm.formJurnalSiswa.tanggal
            const params = {
                req: 'table',
                kelas_id: kelasId
            }
            vm.axios
                .get(vm.url('siswa/read'), { params })
                .then((response) => {
                    vm.studentData = response.data.models
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        getKehadiranSiswaData(tanggal) {
            const vm = this
            const params = {
                req: 'by_kelas',
                kelas_id: vm.formJurnalSiswa.kelas,
                user_id: vm.formJurnalSiswa.nama,
                tanggal: tanggal
            }

            vm.axios
                .get(vm.url('kehadiran/read'), { params })
                .then((response) => {
                    if (response.data.models[0].kehadiran.length > 0) {
                        vm.formJurnalSiswa.kehadiran =
                            response.data.models[0].kehadiran[0].kehadiran
                    } else {
                        if (vm.formJurnalSiswa.kehadiran) {
                            delete vm.formJurnalSiswa.kehadiran
                        }
                    }
                })
                .catch((e) =>
                    vm.$notification.error({
                        message: 'Error',
                        description: e
                    })
                )
        },
        getJurnalSiswa() {
            const vm = this
            vm.axios
                .get(vm.url('piket/jurnal/siswa'))
                .then((res) => {
                    vm.jurnalSiswaDataSources = res.data.data.map(
                        (item, index) => {
                            return {
                                id: item.id,
                                no: index + 1,
                                nama: item.kehadiran_siswa.siswa.user.name,
                                kehadiran: item.kehadiran_siswa.kehadiran,
                                tanggal: moment(
                                    item.kehadiran_siswa.tanggal
                                ).format('LL'),
                                jam: item.jam.substring(0, 5),
                                keterangan: null
                            }
                        }
                    )
                })

                .catch((err) => {})
        },
        showJurnalSiswa(jurnalSiswaId) {
            const vm = this
            vm.formJurnalSiswa = {}
            vm.modalVisible.jurnalSiswa = true
            vm.editMode.jurnalSiswa = true
            vm.axios
                .get(vm.url('piket/jurnal/siswa/' + jurnalSiswaId))
                .then((res) => {
                    const item = res.data.data
                    vm.getStudentDatabyKelasId(
                        item.kehadiran_siswa.siswa.kelas.id
                    )
                    vm.formJurnalSiswa = {
                        id: item.id,
                        kelas: item.kehadiran_siswa.siswa.kelas.id,
                        nama: item.kehadiran_siswa.user_id,
                        tanggal: item.kehadiran_siswa.tanggal,
                        kehadiran: item.kehadiran_siswa.kehadiran,
                        jam: item.jam
                    }
                })
                .catch((err) => {})
        },
        editJurnalSiswa() {
            const vm = this
            const data = {
                id: vm.formJurnalSiswa.id,
                user_id: vm.formJurnalSiswa.nama,
                kelas_id: vm.formJurnalSiswa.kelas,
                kehadiran: vm.formJurnalSiswa.kehadiran,
                tanggal: vm.formJurnalSiswa.tanggal,
                jam: vm.formJurnalSiswa.jam
            }

            vm.axios
                .post(vm.url('piket/jurnal/siswa'), data)
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: res.data.message
                    })
                    this.getJurnalSiswa()
                    vm.modalVisible.jurnalSiswa = false
                    vm.editMode.jurnalSiswa = false
                    vm.formJurnalSiswa = {}
                })
                .catch((err) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: err.response
                    })
                })
        },
        deleteJurnalSiswa(jurnalSiswaId) {
            const vm = this
            vm.axios
                .delete(vm.url('piket/jurnal/siswa/' + jurnalSiswaId))
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Berhasil menghapus data'
                    })
                    vm.getJurnalSiswa()
                })
                .catch((err) => {})
        }
    }
}
</script>
