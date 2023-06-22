<template>
    <h1 style="margin-left: 30px">Laporan Penilaian Siswa</h1>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="Pilih Kriteria"
                style="width: 100%; margin-bottom: 20px"
            >
                <a-form
                    layout="vertical"
                    :model="filter"
                    ref="filter"
                    @finish="checkAndFetch"
                >
                    <a-space
                        style="
                            display: flex;
                            justify-content: flex-container;
                            flex-wrap: wrap;
                            margin-bottom: 20px;
                        "
                    >
                        <a-form-item label="Masukkan kelas" name="kelas">
                            <a-select
                                v-model:value="filter.kelas_id"
                                style="width: 345px"
                                placeholder="Pilih kelas"
                                @change="getMapelByKelasId"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Kelas harus dipilih'
                                    }
                                ]"
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
                            label="Pilih mata pelajaran"
                            name="mapel"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Mata pelajaran harus dipilih'
                                }
                            ]"
                        >
                            <a-select
                                v-model:value="filter.mapel"
                                style="width: 256px"
                                placeholder="Pilih mata pelajaran"
                            >
                                <a-select-option
                                    v-for="mapel in mapels"
                                    :key="mapel.mapel_hari_id"
                                    :value="mapel.mapel_hari_id"
                                >
                                    {{ mapel.nama }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-space>
                    <a-space
                        direction="horizontal"
                        style="display: flex; justify-content: flex-end"
                    >
                        <a-button
                            type="primary"
                            htmlType="submit"
                            style="display: inline-flex; align-items: center"
                        >
                            <template #icon><SearchOutlined /></template>
                            Cari
                        </a-button>
                    </a-space>
                </a-form>
            </a-card>
            <a-card
                v-if="dataReady"
                :loading="fetching"
                title="Daftar Siswa"
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
                        v-model:value="filter.search"
                        placeholder="Cari Siswa"
                        style="width: 200px"
                        @search="readData"
                    />
                    <a-button type="primary" @click="loadInputNilai"
                        >Tambah Nilai</a-button
                    >
                    <a-button>
                        <template #icon><download-outlined /></template>
                    </a-button>
                </a-space>
                <a-table
                    :columns="columns"
                    :data-source="data"
                    style="margin: 15px"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'nama'">
                            <a>
                                {{ record.nama }}
                            </a>
                        </template>
                        <template v-else-if="column.key === 'kehadiran'">
                            <div>
                                <a-radio-group v-model:value="value">
                                    <a-radio :value="1">Hadir</a-radio>
                                    <a-radio :value="2">Absen</a-radio>
                                    <a-radio :value="3">Telat</a-radio>
                                    <a-radio :value="4">Setengah Hari</a-radio>
                                    <a-radio :value="5"
                                        >Absen dengan Keterangan</a-radio
                                    >
                                </a-radio-group>
                            </div>
                        </template>
                        <template v-else-if="column.key === 'action'">
                            <span>
                                <a-button
                                    type=""
                                    :size="size"
                                    @click="loadInputNilai"
                                >
                                    <template #icon>
                                        <edit-outlined
                                            :style="{ color: '#000000' }"
                                        />
                                    </template>
                                </a-button>
                            </span>
                        </template>
                    </template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>
    <a-modal v-model:visible="inputModal" title="Input Nilai">
        <a-form
            :model="formInputNilai"
            ref="formInputNilai"
            :label-col="labelCol"
        >
            <a-form-item :name="nis" label="NIS/NISN">
                <a-input
                    v-model:value="formInputNilai.nis"
                    placeholder="Masukkan NIS/NISN"
                />
            </a-form-item>
            <a-form-item :name="nama" label="Nama Siswa">
                <a-input
                    v-model:value="formInputNilai.nama"
                    placeholder="Masukkan nama siswa"
                />
            </a-form-item>
            <a-form-item :name="kelas" label="Kelas">
                <a-input
                    v-model:value="formInputNilai.kelas"
                    placeholder="Masukkan kelas"
                />
            </a-form-item>
            <a-form-item :name="mapel" label="Mata Pelajaran">
                <a-input
                    v-model:value="formInputNilai.mapel"
                    placeholder="Masukkan mata pelajaran"
                />
            </a-form-item>
            <a-form-item
                :name="harian"
                label="Nilai Harian"
                :wrapper-col="{ span: 3 }"
            >
                <a-input v-model:value="formInputNilai.harian" />
            </a-form-item>
            <a-form-item
                :name="tugas"
                label="Nilai Tugas"
                :wrapper-col="{ span: 3 }"
            >
                <a-input v-model:value="formInputNilai.tugas" />
            </a-form-item>
            <a-form-item
                :name="ujianmid"
                label="Nilai Ujian Mid"
                :wrapper-col="{ span: 3 }"
            >
                <a-input v-model:value="formInputNilai.ujianmid" />
            </a-form-item>
            <a-form-item
                :name="ujiansemester"
                label="Nilai Ujian Semester"
                :wrapper-col="{ span: 3 }"
            >
                <a-input v-model:value="formInputNilai.ujiansemester" />
            </a-form-item>
        </a-form>
    </a-modal>
</template>

<script>
const columns = [
    {
        title: 'No.',
        dataIndex: 'key',
        key: 'no'
    },
    {
        title: 'NIS/NISN',
        dataIndex: 'nis',
        key: 'nis'
    },
    {
        title: 'Nama Siswa',
        dataIndex: 'nama',
        key: 'nama'
    },
    {
        title: 'Kelas',
        dataIndex: 'kelas',
        key: 'kelas'
    },
    {
        title: 'Pelajaran',
        dataIndex: 'pelajaran',
        key: 'pelajaran'
    }
]
const data = [
    {
        key: '1',
        nis: 2910398212,
        nama: 'Muhammad Farhan Syahreza',
        kelas: 'VIII A',
        pelajaran: 'B.Indo',
        nilai_harian: '90',
        nilai_tugas: '90',
        nilai_ujian_mid: '90',
        nilai_ujian_semester: '90'
    },
    {
        key: '1',
        nis: 2910398212,
        nama: 'Muhammad Farhan Syahreza',
        kelas: 'VIII A',
        pelajaran: 'B.Indo',
        nilai_harian: '90',
        nilai_tugas: '90',
        nilai_ujian_mid: '90',
        nilai_ujian_semester: '90'
    },
    {
        key: '1',
        nis: 2910398212,
        nama: 'Muhammad Farhan Syahreza',
        kelas: 'VIII A',
        pelajaran: 'B.Indo',
        nilai_harian: '90',
        nilai_tugas: '90',
        nilai_ujian_mid: '90',
        nilai_ujian_semester: '90'
    }
]
export default {
    data() {
        return {
            data,
            columns,
            classes: [],
            dataReady: false,
            models: [],
            mapels: [],
            filter: {},
            tugas: [],
            fetching: false,
            inputModal: false,
            formInputNilai: false,
            labelCol: { style: { width: '150px' } }
        }
    },
    mounted() {
        this.getAllKelas()
    },
    methods: {
        getAllKelas() {
            const vm = this
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
        checkAndFetch() {
            const vm = this
            console.log(vm.filter)
            vm.axios
                .get(
                    vm.url(
                        'laporan/tugas-siswa/mata-pelajaran-hari/' +
                            vm.filter.mapel
                    )
                )
                .then((response) => {
                    vm.tugas = response.data.data.map((tugas) => {
                        return {
                            id: tugas.id,
                            jenis_tugas: tugas.jenis_tugas,
                            tanggal: tugas.tanggal
                        }
                    })

                    vm.siswa =
                        response.data.data[0].mata_pelajaran.kelas.siswa.map(
                            (siswa) => {
                                return {
                                    id: siswa.id,
                                    user_id: siswa.user.id,
                                    name: siswa.user.name,
                                    nisn: siswa.nisn
                                }
                            }
                        )

                    vm.tugas.forEach((tugas) => {
                        vm.columns.push({
                            title: tugas.jenis_tugas,
                            dataIndex: `${tugas.jenis_tugas.replace(
                                /\s+/g,
                                '_'
                            )}`
                        })
                    })

                    console.log(vm.columns)
                })
                .catch((e) => {
                    console.log(e)
                })
        },
        getMapelByKelasId(kelas_id) {
            const vm = this
            delete vm.filter.mapel
            const params = {
                req: 'get_mapel_by_kelas_id',
                kelas_id
            }

            vm.axios
                .get(vm.url('mapel/read'), { params })
                .then((response) => {
                    vm.mapels = response.data.models.map((mapel) => {
                        return {
                            id: mapel.mapel.id,
                            nama: mapel.mapel.name,
                            mapel_hari_id: mapel.id
                        }
                    })
                })
                .catch((e) => {
                    vm.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: e.response
                    })
                })
        },
        readData() {
            const vm = this
            vm.dataReady = false
            vm.fetching = true
            const params = {
                req: 'table',
                ...vm.filter
            }
            vm.axios
                .get(vm.url('nilai/read'), { params })
                .then((response) => {
                    vm.models = response.data.models
                })
                .then(() => {
                    vm.dataReady = true
                    vm.fetching = false
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        loadInputNilai() {
            this.inputModal = true
        }
    }
}
</script>
