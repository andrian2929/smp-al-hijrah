<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <h1>Jurnal Guru</h1>
            <a-card style="border-radius: 20px">
                <a-tabs v-model:activeKey="activeKey">
                    <a-tab-pane key="1" tab="Ketertiban Guru">
                        <a-button
                            type="primary"
                            @click="
                                () => {
                                    modalEditMode.ketertibanGuru = false
                                    modalVisible.ketertibanGuru = true
                                    formKetertibanGuru = {
                                        tanggal: currentDate,
                                        jam: currentTime
                                    }
                                }
                            "
                            >Tambah</a-button
                        >
                        <div style="overflow: auto">
                            <a-table
                                :data-source="ketertibanGuruDataSources"
                                :columns="ketertibanGuruColumns"
                                style="margin-top: 10px"
                            >
                                <template #bodyCell="{ column, record }">
                                    <template
                                        v-if="column.dataIndex === 'aksi'"
                                    >
                                        <a-space>
                                            <a-button
                                                type="primary"
                                                @click="
                                                    () => {
                                                        modalEditMode.ketertibanGuru = true
                                                        showKetertibanGuru(
                                                            record.id
                                                        )
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
                                                        deleteKetertibanGuru
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
                    </a-tab-pane>
                    <a-tab-pane key="2" tab="Guru Pengganti">
                        <a-button
                            type="primary"
                            @click="
                                () => {
                                    formGuruPengganti = {
                                        jam: currentTime,
                                        tanggal: currentDate
                                    }

                                    modalVisible.guruPengganti = true
                                    modalEditMode.guruPengganti = false
                                }
                            "
                            >Tambah</a-button
                        >

                        <div style="overflow: auto">
                            <a-table
                                :data-source="guruPenggantiDataSources"
                                :columns="guruPenggantiColumns"
                                style="margin-top: 10px"
                            >
                                <template #bodyCell="{ column, record }">
                                    <template
                                        v-if="column.dataIndex === 'aksi'"
                                    >
                                        <a-space>
                                            <a-button
                                                type="primary"
                                                @click="
                                                    showGuruPengganti(record.id)
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
                                                        deleteGuruPengganti
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
                    </a-tab-pane>
                </a-tabs>
            </a-card>
        </a-col>
    </a-row>

    <a-modal
        v-model:visible="modalVisible.ketertibanGuru"
        title="Ketertiban Guru"
    >
        <template #footer></template>
        <a-form
            layout="vertical"
            :model="formKetertibanGuru"
            @finish="
                modalEditMode.ketertibanGuru
                    ? editKetertibanGuru()
                    : saveKetertibanGuru()
            "
            ref="formKetertibanGuru"
        >
            <a-form-item
                label="Guru"
                name="guru"
                :rules="[
                    {
                        required: true,
                        message: 'Guru harus dipilih'
                    }
                ]"
            >
                <a-select
                    v-model:value="formKetertibanGuru.guru"
                    placeholder="Pilih guru"
                >
                    <a-select-option
                        v-for="_gr in guru"
                        :key="_gr.id"
                        :value="_gr.id"
                    >
                        {{ _gr.name }}
                    </a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item
                label="Tanggal"
                name="tanggal"
                :rules="[
                    {
                        required: true,
                        message: 'Tanggal harus diisi'
                    },
                    {
                        pattern: new RegExp(
                            /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/
                        ),
                        message: 'Format tanggal tidak valid'
                    }
                ]"
            >
                <a-date-picker
                    style="width: 100%"
                    v-model:value="formKetertibanGuru.tanggal"
                    placeholder="Masukkan tanggal"
                    value-format="YYYY-MM-DD"
                />
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
                    v-model:value="formKetertibanGuru.jam"
                    format="HH:mm"
                    value-format="HH:mm"
                    placeholder="Masukkan jam"
                    style="width: 100%"
                />
            </a-form-item>

            <a-form-item label="Keterangan" name="keterangan">
                <a-textarea
                    v-model:value="formKetertibanGuru.keterangan"
                    placeholder="Masukkan keterangan"
                    :rows="4"
                    style="width: 100%"
                />
            </a-form-item>

            <a-form-item>
                <a-button
                    type="primary"
                    style="display: inline-flex; align-items: center"
                    htmlType="submit"
                >
                    Simpan
                </a-button>
            </a-form-item>
        </a-form>
    </a-modal>

    <a-modal
        v-model:visible="modalVisible.guruPengganti"
        title="Ketertiban Guru"
    >
        <template #footer></template>
        <a-form
            layout="vertical"
            :model="formGuruPengganti"
            @finish="
                modalEditMode.guruPengganti
                    ? editGuruPengganti()
                    : saveGuruPengganti()
            "
            ref="formGuruPengganti"
        >
            <a-form-item
                label="Guru"
                name="guru"
                :rules="[
                    {
                        required: true,
                        message: 'Guru harus dipilih'
                    }
                ]"
            >
                <a-select
                    v-model:value="formGuruPengganti.guru"
                    placeholder="Pilih guru"
                    @change="getMapelByIdGuru"
                >
                    <a-select-option
                        v-for="_gr in guru"
                        :key="_gr.user_id"
                        :value="_gr.user_id"
                    >
                        {{ _gr.name }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item
                label="Mata Pelajaran"
                name="mataPelajaran"
                :rules="[
                    {
                        required: true,
                        message: 'Mata pelajaran harus dipilih terlebih dahulu'
                    }
                ]"
            >
                <a-select
                    v-model:value="formGuruPengganti.mataPelajaran"
                    placeholder="Pilih mata pelajaran"
                >
                    <a-select-option
                        v-for="mapel in mataPelajaran"
                        :key="mapel.id"
                        :value="mapel.id"
                    >
                        {{ mapel.name }} - {{ mapel.kelas }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item
                label="Guru Pengganti"
                name="guruPengganti"
                :rules="[
                    {
                        required: true,
                        message: 'Guru pengganti harus dipilih'
                    }
                ]"
            >
                <a-select
                    v-model:value="formGuruPengganti.guruPengganti"
                    placeholder="Pilih guru pengganti"
                >
                    <a-select-option
                        v-for="_gr in guru"
                        :key="_gr.id"
                        :value="_gr.id"
                    >
                        {{ _gr.name }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item
                label="Tanggal"
                name="tanggal"
                :rules="[
                    {
                        required: true,
                        message: 'Tanggal harus diisi'
                    },
                    {
                        pattern: new RegExp(
                            /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/
                        ),
                        message: 'Format tanggal tidak valid'
                    }
                ]"
            >
                <a-date-picker
                    style="width: 100%"
                    v-model:value="formGuruPengganti.tanggal"
                    placeholder="Masukkan tanggal"
                    value-format="YYYY-MM-DD"
                />
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
                    v-model:value="formGuruPengganti.jam"
                    format="HH:mm"
                    value-format="HH:mm"
                    placeholder="Masukkan jam"
                    style="width: 100%"
                />
            </a-form-item>

            <a-form-item label="Keterangan" name="keterangan">
                <a-textarea
                    v-model:value="formGuruPengganti.keterangan"
                    placeholder="Masukkan keterangan"
                    :rows="4"
                    style="width: 100%"
                />
            </a-form-item>

            <a-form-item>
                <a-button
                    type="primary"
                    style="display: inline-flex; align-items: center"
                    htmlType="submit"
                >
                    Simpan
                </a-button>
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script>
const ketertibanGuruColumns = [
    {
        title: 'No',
        dataIndex: 'no',
        key: 'no'
    },

    {
        title: 'Nama',
        dataIndex: 'nama',
        key: 'nama',
        width: 200
    },
    {
        title: 'Tanggal',
        dataIndex: 'tanggal',
        key: 'tanggal'
    },
    {
        title: 'Jam',
        dataIndex: 'jam',
        key: 'jam'
    },
    {
        title: 'Keterangan',
        dataIndex: 'keterangan',
        key: 'keterangan',
        width: 400
    },
    {
        title: 'Aksi',
        dataIndex: 'aksi',
        key: 'aksi'
    }
]

const guruPenggantiColumns = [
    {
        title: 'No',
        dataIndex: 'no',
        key: 'no'
    },
    {
        title: 'Nama',
        dataIndex: 'nama',
        key: 'nama'
    },
    {
        title: 'Tanggal',
        dataIndex: 'tanggal',
        key: 'tanggal'
    },
    {
        title: 'Jam',
        dataIndex: 'jam',
        key: 'jam'
    },
    {
        title: 'Pengganti',
        dataIndex: 'pengganti',
        key: 'pengganti'
    },
    {
        title: 'Mata Pelajaran',
        dataIndex: 'mataPelajaran',
        key: 'mataPelajaran'
    },
    {
        title: 'Keterangan',
        dataIndex: 'keterangan',
        key: 'keterangan'
    },
    {
        title: 'Aksi',
        dataIndex: 'aksi',
        key: 'aksi'
    }
]

import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue'
import moment from 'moment'
import 'moment/locale/id'

export default {
    data() {
        return {
            activeKey: '1',
            ketertibanGuruDataSources: [],
            ketertibanGuruColumns,
            guruPenggantiDataSources: [],
            guruPenggantiColumns,
            modalVisible: {
                ketertibanGuru: false,
                guruPengganti: false
            },
            modalEditMode: {
                ketertibanGuru: false,
                guruPengganti: false
            },
            formKetertibanGuru: {},
            formGuruPengganti: {},
            guru: [],
            mataPelajaran: []
        }
    },
    mounted() {
        this.getKetertibanGuruData()
        this.getGuruPenggantiData()
        this.getAllGuru()
    },
    computed: {
        currentDate() {
            return moment().format('YYYY-MM-DD')
        },
        currentTime() {
            return moment().format('HH:mm')
        }
    },
    methods: {
        getAllGuru() {
            const vm = this
            vm.axios
                .get(vm.url('guru/read'), { params: { req: 'table' } })
                .then((res) => {
                    console.log(res)
                    vm.guru = res.data.models.map((item, index) => {
                        return {
                            id: item.guru.id,
                            user_id: item.id,
                            name: item.name
                        }
                    })
                })
        },
        saveKetertibanGuru() {
            const vm = this
            const { guru: guru_id, ...rest } = vm.formKetertibanGuru
            const data = {
                ...rest,
                guru_id
            }

            vm.axios
                .post(vm.url('piket/jurnal/guru/ketertiban'), data)
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil disimpan'
                    })
                    this.getKetertibanGuruData()
                    vm.modalVisible.ketertibanGuru = false
                })
        },
        getKetertibanGuruData() {
            const vm = this
            vm.axios.get(vm.url('piket/jurnal/guru/ketertiban')).then((res) => {
                vm.ketertibanGuruDataSources = res.data.data.map(
                    (item, index) => {
                        return {
                            no: index + 1,
                            id: item.id,
                            nama: item.guru.user.name,
                            tanggal: moment(item.tanggal).format('LL'),
                            jam: item.jam.substring(0, 5),
                            keterangan: item.keterangan
                        }
                    }
                )
            })
        },
        deleteKetertibanGuru(id) {
            const vm = this
            vm.axios
                .delete(vm.url('piket/jurnal/guru/ketertiban/' + id))
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil dihapus'
                    })
                    this.getKetertibanGuruData()
                })
        },
        showKetertibanGuru(id) {
            const vm = this
            vm.formKetertibanGuru = {}
            vm.axios
                .get(vm.url('piket/jurnal/guru/ketertiban/' + id))
                .then((res) => {
                    vm.formKetertibanGuru = {
                        id: res.data.data.id,
                        guru: res.data.data.guru_id,
                        tanggal: res.data.data.tanggal,
                        jam: res.data.data.jam.substring(0, 5),
                        keterangan: res.data.data.keterangan
                    }
                    vm.modalVisible.ketertibanGuru = true
                })
        },
        editKetertibanGuru() {
            const vm = this
            const { guru: guru_id, ...rest } = vm.formKetertibanGuru
            const data = {
                ...rest,
                guru_id
            }

            vm.axios
                .put(vm.url('piket/jurnal/guru/ketertiban/' + data.id), data)
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil diubah'
                    })
                    this.getKetertibanGuruData()
                    vm.modalVisible.ketertibanGuru = false
                    vm.modalEditMode.ketertibanGuru = false
                    vm.formKetertibanGuru = {}
                })
                .catch((err) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: err.response.data.data
                    })
                })
        },
        getGuruPenggantiData() {
            const vm = this
            vm.axios.get(vm.url('piket/jurnal/guru/pengganti')).then((res) => {
                vm.guruPenggantiDataSources = res.data.data.map(
                    (item, index) => {
                        return {
                            no: index + 1,
                            id: item.id,
                            nama: item.guru.user.name,
                            tanggal: moment(item.tanggal).format('LL'),
                            jam: item.jam.substring(0, 5),
                            pengganti: item.guru_pengganti.user.name,
                            mataPelajaran: item.mata_pelajaran.mapel.name,
                            keterangan: item.keterangan
                        }
                    }
                )

                console.log(vm.guruPenggantiDataSources)
            })
        },
        getMapelByIdGuru(id) {
            const vm = this
            const params = {
                req: 'get_mapel_by_guru_id',
                guru_id: id
            }
            vm.axios.get(vm.url('mapel/read'), { params }).then((res) => {
                console.log(res.data.models)
                vm.mataPelajaran = res.data.models.map((item, index) => {
                    return {
                        id: item.id,
                        name: item.mapel.name,
                        kelas: `${item.kelas.jenjang} - ${item.kelas.section}`
                    }
                })
            })
        },
        saveGuruPengganti() {
            const vm = this
            const guru_id = vm.guru.find((item) => {
                return item.user_id == vm.formGuruPengganti.guru
            }).id

            const data = {
                guru_id: guru_id,
                mata_pelajaran_id: vm.formGuruPengganti.mataPelajaran,
                guru_pengganti_id: vm.formGuruPengganti.guruPengganti,
                tanggal: vm.formGuruPengganti.tanggal,
                jam: vm.formGuruPengganti.jam,
                keterangan: vm.formGuruPengganti.keterangan
            }

            vm.axios
                .post(vm.url('piket/jurnal/guru/pengganti'), data)
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil disimpan'
                    })
                    this.getGuruPenggantiData()
                    vm.modalVisible.guruPengganti = false
                    vm.formGuruPengganti = {}
                })
                .catch((err) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: err.response.data.data
                    })
                })
        },
        deleteGuruPengganti(id) {
            const vm = this
            vm.axios
                .delete(vm.url('piket/jurnal/guru/pengganti/' + id))
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil dihapus'
                    })
                    this.getGuruPenggantiData()
                })
        },
        showGuruPengganti(id) {
            const vm = this
            vm.formGuruPengganti = {}
            vm.modalEditMode.guruPengganti = true
            console.log(id)
            vm.axios
                .get(vm.url('piket/jurnal/guru/pengganti/' + id))
                .then((res) => {
                    const guru_id = vm.guru.find((item) => {
                        return item.id == res.data.data.id
                    }).user_id

                    console.log(guru_id)
                    vm.getMapelByIdGuru(guru_id)
                    vm.formGuruPengganti = {
                        id: res.data.data.id,
                        guru: guru_id,
                        tanggal: res.data.data.tanggal,
                        jam: res.data.data.jam.substring(0, 5),
                        guruPengganti: res.data.data.guru_pengganti_id,
                        mataPelajaran: res.data.data.mata_pelajaran_id,
                        keterangan: res.data.data.keterangan
                    }
                    vm.modalVisible.guruPengganti = true
                })
        },
        editGuruPengganti() {
            const vm = this
            const guru_id = vm.guru.find((item) => {
                return item.user_id == vm.formGuruPengganti.guru
            }).id

            const data = {
                id: vm.formGuruPengganti.id,
                guru_id: guru_id,
                mata_pelajaran_id: vm.formGuruPengganti.mataPelajaran,
                guru_pengganti_id: vm.formGuruPengganti.guruPengganti,
                tanggal: vm.formGuruPengganti.tanggal,
                jam: vm.formGuruPengganti.jam,
                keterangan: vm.formGuruPengganti.keterangan
            }

            console.log(data)

            vm.axios
                .put(vm.url('piket/jurnal/guru/pengganti/' + data.id), data)
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil diubah'
                    })
                    this.getGuruPenggantiData()
                    vm.modalVisible.guruPengganti = false
                    vm.modalEditMode.guruPengganti = false
                    vm.formGuruPengganti = {}
                })
                .catch((err) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: err.response.data.data
                    })
                })
        }
    }
}
</script>
