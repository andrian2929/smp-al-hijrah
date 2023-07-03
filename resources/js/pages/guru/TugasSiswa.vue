<template>
    <h1 style="margin-left: 30px">Penilaian Akademik Siswa</h1>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card :loading="fetching" style="width: 100%">
                <a-button
                    type="primary"
                    @click="
                        () => {
                            this.modal.tambahTugas = true
                            this.editMode = false
                            this.formTambahTugas = {}
                        }
                    "
                >
                    <template #icon><PlusOutlined /></template>
                    Tambah
                </a-button>

                <a-row
                    type="flex"
                    justify="space-between"
                    style="margin-bottom: 15px; margin-top: 20px"
                >
                    <a-col>
                        <a-form
                            :model="filter"
                            @finish="onFinishFilter"
                            @finishFailed="onFinishFailedFilter"
                            ref="filter"
                            :layout="'vertical'"
                        >
                            <a-space
                                style="
                                    display: flex;
                                    justify-content: flex-container;
                                    flex-wrap: wrap;
                                "
                            >
                                <a-form-item
                                    name="kelasId"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Pilih kelas terlebih dahulu',
                                            trigger: 'blur'
                                        }
                                    ]"
                                    label="Kelas"
                                >
                                    <a-select
                                        v-model:value="filter.kelasId"
                                        style="width: 345px"
                                        placeholder="Pilih kelas"
                                        @change="getMapelByIdGuru"
                                    >
                                        <a-select-option value="all"
                                            >Semua</a-select-option
                                        >
                                        <a-select-option
                                            v-for="cls in classes"
                                            :key="cls.id"
                                            :value="cls.id"
                                            >{{ cls.jenjang }} -
                                            {{ cls.section }}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                                <a-form-item
                                    name="mataPelajaran"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Pilih mata pelajaran terlebih dahulu',
                                            trigger: 'blur'
                                        }
                                    ]"
                                    label="Mata Pelajaran"
                                >
                                    <a-select
                                        v-model:value="filter.mataPelajaran"
                                        placeholder="Pilih mata pelajaran"
                                        style="width: 345px"
                                    >
                                        <a-select-option value="all"
                                            >Semua</a-select-option
                                        >
                                        <a-select-option
                                            v-for="mapel in mapels"
                                            :key="mapel.id"
                                            :value="mapel.id"
                                            >{{ mapel.mapel.name }}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-space>
                            <a-space>
                                <a-form-item>
                                    <a-button
                                        type="danger"
                                        style="align-items: center"
                                        html-type="submit"
                                    >
                                        <template #icon
                                            ><SearchOutlined
                                        /></template>
                                        Cari
                                    </a-button>
                                </a-form-item>
                            </a-space>
                        </a-form>
                    </a-col>

                    <a-col>
                        <a-button
                            type="primary"
                            html-type="button"
                            @click="
                                () => {
                                    modal.cetakNilai = true
                                }
                            "
                        >
                            <template #icon>
                                <PrinterOutlined />
                            </template>
                            Cetak
                        </a-button>
                    </a-col>
                </a-row>
                <a-table
                    :columns="columns"
                    :data-source="tugas"
                    style="margin: 15px"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'aksi'">
                            <a-space>
                                <a-button
                                    @click="showSiswa(record.id)"
                                    type="primary"
                                >
                                    <template #icon><PlusOutlined /></template>
                                </a-button>
                                <a-button
                                    type="primary"
                                    @click="showTugas(record.id)"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    type="danger"
                                    @click="alert(record.id, deleteTugas)"
                                >
                                    <template #icon
                                        ><DeleteOutlined
                                    /></template>
                                </a-button>
                            </a-space>
                        </template>
                    </template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>

    <a-modal v-model:visible="modal.tambahTugas" title="Tambah Tugas">
        <template #footer></template>
        <a-form
            layout="vertical"
            :model="formTambahTugas"
            @finish="editMode ? editTugas() : addTugas()"
            ref="formTambahTugas"
        >
            <a-form-item
                label="Kelas"
                name="kelas"
                :rules="[
                    {
                        required: true,
                        message: 'Kelas harus diisi'
                    }
                ]"
            >
                <a-select
                    v-model:value="formTambahTugas.kelas"
                    placeholder="Kelas"
                    @change="getMapelByIdGuru"
                >
                    <a-select-option
                        v-for="cls in classes"
                        :key="cls.id"
                        :value="cls.id"
                        >{{ cls.jenjang }} - {{ cls.section }}
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
                    v-model:value="formTambahTugas.tanggal"
                    placeholder="Tanggal"
                    value-format="YYYY-MM-DD"
                />
            </a-form-item>
            <a-form-item
                label="Mata Pelajaran"
                name="mataPelajaran"
                :rules="[
                    {
                        required: true,
                        message: 'Mata Pelajaran harus diisi'
                    }
                ]"
            >
                <a-select
                    v-model:value="formTambahTugas.mataPelajaran"
                    placeholder="Pilih mata pelajaran"
                >
                    <a-select-option
                        v-for="mapel in mapels"
                        :key="mapel.id"
                        :value="mapel.id"
                        >{{ mapel.mapel.name }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item
                label="Jenis Tugas"
                name="jenisTugas"
                :rules="[
                    {
                        required: true,
                        message: 'Jenis Tugas harus diisi'
                    }
                ]"
            >
                <a-select
                    v-model:value="formTambahTugas.jenisTugas"
                    placeholder="Jenis Tugas"
                >
                    <a-select-option value="uts">UTS</a-select-option>
                    <a-select-option value="uas">UAS</a-select-option>
                    <a-select-option value="ulangan-1"
                        >Ulangan 1</a-select-option
                    >
                    <a-select-option value="ulangan-2"
                        >Ulangan 2</a-select-option
                    >
                    <a-select-option value="ulangan-3"
                        >Ulangan 3</a-select-option
                    >
                    <a-select-option value="ulangan-4"
                        >Ulangan 4</a-select-option
                    >
                    <a-select-option value="ulangan-5"
                        >Ulangan 5</a-select-option
                    >
                    <a-select-option value="ulangan-6"
                        >Ulangan 6</a-select-option
                    >
                    <a-select-option value="ulangan-7"
                        >Ulangan 7</a-select-option
                    >
                    <a-select-option value="ulangan-8"
                        >Ulangan 8</a-select-option
                    >
                    <a-select-option value="ulangan-9"
                        >Ulangan 9</a-select-option
                    >
                    <a-select-option value="ulangan-10"
                        >Ulangan 10</a-select-option
                    >
                </a-select>
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

    <a-modal v-model:visible="modal.tambahNilai" title="Tambah Nilai Siswa">
        <template #footer></template>
        <a-form
            :model="formTambahNilai"
            @finish="addNilai()"
            ref="formTambahNilai"
        >
            <a-table :columns="tambahNilaiColumns" :data-source="siswa">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'nilai'">
                        <a-form-item
                            :name="[`nilai_${record.siswa_id}`, 'nilai']"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Nilai harus diisi'
                                },
                                {
                                    pattern: new RegExp(/^[0-9]+$/),
                                    message: 'Nilai harus berupa angka'
                                }
                            ]"
                        >
                            <a-input-number
                                v-model:value="
                                    formTambahNilai[`nilai_${record.siswa_id}`]
                                        .nilai
                                "
                                :min="0"
                                :max="100"
                            />
                        </a-form-item>
                    </template>
                </template>
            </a-table>
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

    <a-modal v-model:visible="modal.cetakNilai" title="Cetak nilai siswa">
        <template #footer></template>
        <a-form
            :model="filterCetakNilai"
            @finish="onfinishCetakNilaiFilter"
            ref="filterCetakNilai"
            :layout="'horizontal'"
            v-bind="{
                labelCol: {
                    span: 8
                },
                wrapperCol: {
                    span: 10
                }
            }"
        >
            <a-form-item
                name="kelasId"
                :rules="[
                    {
                        required: true,
                        message: 'Pilih kelas terlebih dahulu',
                        trigger: 'blur'
                    }
                ]"
                label="Kelas"
            >
                <a-select
                    v-model:value="filterCetakNilai.kelasId"
                    style="width: 300px"
                    placeholder="Pilih kelas"
                    @change="getMapelByIdGuru"
                >
                    <a-select-option
                        v-for="cls in classes"
                        :key="cls.id"
                        :value="cls.id"
                        >{{ cls.jenjang }} -
                        {{ cls.section }}
                    </a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item
                name="mataPelajaran"
                :rules="[
                    {
                        required: true,
                        message: 'Pilih mata pelajaran terlebih dahulu',
                        trigger: 'blur'
                    }
                ]"
                label="Mata Pelajaran"
            >
                <a-select
                    v-model:value="filterCetakNilai.mataPelajaran"
                    placeholder="Pilih mata pelajaran"
                    style="width: 300px"
                >
                    <a-select-option
                        v-for="mapel in mapels"
                        :key="mapel.id"
                        :value="mapel.id"
                        >{{ mapel.mapel.name }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item>
                <a-button
                    type="danger"
                    style="align-items: center"
                    html-type="submit"
                >
                    <template #icon><PrinterOutlined /></template>
                    Cetak
                </a-button>
            </a-form-item>
        </a-form>
    </a-modal>
</template>

<script>
import { message } from 'ant-design-vue'
import FileSaver from 'file-saver'

import {
    SearchOutlined,
    PlusOutlined,
    UploadOutlined,
    FormOutlined,
    FileTextOutlined,
    EditOutlined,
    DeleteOutlined,
    PrinterOutlined
} from '@ant-design/icons-vue'

const columns = [
    {
        title: 'No',
        dataIndex: 'no'
    },
    {
        title: 'Kelas',
        dataIndex: 'kelas'
    },
    {
        title: 'Mata Pelajaran',
        dataIndex: 'mata_pelajaran'
    },
    {
        title: 'Tanggal',
        dataIndex: 'tanggal'
    },

    {
        title: 'Jenis Tugas',
        dataIndex: 'jenis_tugas'
    },
    {
        title: 'Aksi',
        key: 'aksi'
    }
]

const tambahNilaiColumns = [
    {
        title: 'No',
        dataIndex: 'no'
    },
    {
        title: 'Nama Siswa',
        dataIndex: 'nama'
    },
    {
        title: 'Nilai',
        key: 'nilai'
    }
]

export default {
    data() {
        return {
            models: [],
            formTambahTugas: {},
            formTambahNilai: {},
            modal: {
                tambahTugas: false,
                tambahNilai: false,
                cetakNilai: false
            },
            tambahNilaiColumns,
            columns,
            classes: [],
            mapels: [],
            tugas: [],
            siswa: [],
            filter: {
                kelasId: null,
                mataPelajaran: null
            },
            filterCetakNilai: {
                kelasId: null,
                mataPelajaran: null
            },
            dataReady: false,
            fetching: false,
            editMode: false
        }
    },
    mounted() {
        this.getAllKelas()
        this.getTugas()
    },
    methods: {
        getAllKelas() {
            const vm = this
            vm.formTambahTugas.mapels = null
            vm.loading = true
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
        getMapelByIdGuru(kelasId) {
            const vm = this
            delete vm.formTambahTugas.mataPelajaran
            vm.filter.mataPelajaran = null
            vm.filterCetakNilai.mataPelajaran = null
            vm.mapels = []
            vm.axios
                .get(vm.url('user'))
                .then((response) => {
                    const params = {
                        req: 'get_mapel_by_guru_id',
                        kelas_id: kelasId,
                        guru_id: response.data.id
                    }
                    vm.axios
                        .get(vm.url('mapel/read'), { params })
                        .then((response) => {
                            vm.loading = false
                            vm.mapels = response.data.models
                            console.log(vm.mapels)
                        })
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        addTugas() {
            const vm = this
            vm.formTambahTugas = {
                mata_pelajaran_id: vm.formTambahTugas.mataPelajaran,
                tanggal: vm.formTambahTugas.tanggal,
                jenis_tugas: vm.formTambahTugas.jenisTugas
            }
            vm.axios
                .post(vm.url('siswa/tugas'), vm.formTambahTugas)
                .then((response) => {
                    vm.modal.tambahTugas = false
                    this.getTugas()
                    vm.$emit('refresh')
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Tugas berhasil disimpan'
                    })
                })
                .catch((error) => {
                    this.$notification.error({
                        message: 'Gagal',
                        description: error.response.data.message
                    })
                })
        },
        onFinishFilter() {
            const vm = this
            vm.getTugas()
        },
        getTugas() {
            const vm = this
            vm.axios
                .get(vm.url('siswa/tugas'), { params: vm.filter })
                .then((response) => {
                    vm.tugas = response.data.data.map((item, index) => {
                        return {
                            id: item.id,
                            no: index + 1,
                            kelas: `${item.mata_pelajaran.kelas.jenjang} - ${item.mata_pelajaran.kelas.section}`,
                            mata_pelajaran: item.mata_pelajaran.mapel.name,
                            tanggal: item.tanggal,
                            jenis_tugas: item.jenis_tugas.toUpperCase()
                        }
                    })
                })
                .catch((error) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: error.response.data.message
                    })
                })
        },
        deleteTugas(id) {
            const vm = this
            vm.axios
                .delete(vm.url('siswa/tugas/' + id))
                .then((response) => {
                    vm.getTugas()
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil dihapus'
                    })
                })
                .catch((error) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: error.response.data.message
                    })
                })
        },
        showTugas(id) {
            const vm = this
            vm.editMode = true
            vm.formTambahTugas = {}
            vm.axios
                .get(vm.url('siswa/tugas/' + id))
                .then((response) => {
                    console.log(response)
                    const tugas = response.data.data
                    vm.formTambahTugas = {
                        id: tugas.id,
                        kelas: tugas.mata_pelajaran.kelas_id,
                        tanggal: tugas.tanggal,
                        jenisTugas: tugas.jenis_tugas
                    }
                    console.log('andrian')
                    console.log(vm.formTambahTugas)
                    vm.getMapelByIdGuru()
                    vm.formTambahTugas.mataPelajaran = tugas.mata_pelajaran.id
                })
                .catch((error) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: error
                    })
                })
            vm.modal.tambahTugas = true
        },
        editTugas() {
            const vm = this
            vm.formTambahTugas = {
                id: vm.formTambahTugas.id,
                mata_pelajaran_id: vm.formTambahTugas.mataPelajaran,
                tanggal: vm.formTambahTugas.tanggal,
                jenis_tugas: vm.formTambahTugas.jenisTugas
            }
            vm.axios
                .put(
                    vm.url('siswa/tugas/' + vm.formTambahTugas.id),
                    vm.formTambahTugas
                )
                .then((response) => {
                    vm.modal.tambahTugas = false
                    vm.editMode = false
                    this.getTugas()
                    vm.$emit('refresh')
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Tugas berhasil disimpan'
                    })
                })
                .catch((error) => {
                    this.$notification.error({
                        message: 'Gagal',
                        description: error.response.data.message
                    })
                })
        },
        showSiswa(tugasId) {
            const vm = this
            this.formTambahNilai = {}
            vm.axios
                .get(vm.url('siswa/tugas/' + tugasId + '/siswa'))
                .then((response) => {
                    if (response.data.data.nilai.length === 0) {
                        vm.siswa =
                            response.data.data.mata_pelajaran.kelas.siswa.map(
                                (item, index) => {
                                    return {
                                        no: index + 1,
                                        siswa_id: item.id,
                                        nama: item.user.name,
                                        nisn: item.nisn,
                                        nilai: 0
                                    }
                                }
                            )
                    } else {
                        vm.siswa = response.data.data.nilai.map(
                            (item, index) => {
                                return {
                                    no: index + 1,
                                    siswa_id: item.id,
                                    nama: item.user.name,
                                    nisn: item.nisn,
                                    nilai: item.pivot.nilai
                                }
                            }
                        )
                    }
                    vm.siswa.forEach((item) => {
                        this.formTambahNilai = {
                            ...this.formTambahNilai,
                            [`nilai_${item.siswa_id}`]: {
                                siswa_id: item.siswa_id,
                                tugas_id: tugasId,
                                nilai: item.nilai
                            }
                        }
                    })
                    vm.modal.tambahNilai = true
                })
                .catch((error) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: error
                    })
                })
        },
        addNilai() {
            const vm = this
            vm.axios
                .post(
                    vm.url('siswa/tugas/mark'),
                    Object.values(vm.formTambahNilai)
                )
                .then((response) => {
                    vm.modal.tambahNilai = false
                    vm.$emit('refresh')
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Nilai berhasil disimpan'
                    })
                })
                .catch((error) => {
                    this.$notification.error({
                        message: 'Gagal',
                        description: error.response.data.message
                    })
                })
        },
        onfinishCetakNilaiFilter() {
            const vm = this
            vm.axios.get(vm.url('user')).then((res) => {
                vm.axios
                    .get(this.url('siswa/tugas/cetak'), {
                        params: {
                            ...vm.filterCetakNilai,
                            guru_id: res.data.id
                        },
                        responseType: 'blob'
                    })
                    .then((response) => {
                        const fileName =
                            response.headers['content-disposition'].split(
                                'filename='
                            )[1]
                        FileSaver.saveAs(
                            new Blob([response.data], {
                                type: 'application/pdf'
                            }),
                            fileName.substring(1, fileName.length - 1)
                        )

                        vm.$notification.success({
                            message: 'Berhasil',
                            description: 'Data berhasil diunduh'
                        })

                        vm.filterCetakNilai = {
                            kelasId: null,
                            mataPelajaran: null
                        }

                        vm.modal.cetakNilai = false
                    })
                    .catch((e) => {
                        vm.$notification.error({
                            message: 'Terjadi kesalahan'
                        })
                    })
            })
        }
    }
}
</script>
