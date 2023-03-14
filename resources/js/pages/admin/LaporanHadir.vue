<template>
    <h1 style="margin-left: 30px">Laporan Kehadiran Siswa</h1>

    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="Pilih Kriteria"
                style="width: 100%; margin-bottom: 20px"
            >
                <a-form
                    :model="filter"
                    @finish="onFinishFilter"
                    @finishFailed="onFinishFailedFilter"
                    ref="filter"
                >
                    <a-space
                        style="
                            display: flex;
                            justify-content: flex-container;
                            flex-wrap: wrap;
                        "
                    >
                        <a-form-item
                            name="kelas_id"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Pilih kelas terlebih dahulu'
                                }
                            ]"
                        >
                            <a-select
                                v-model:value="filter.kelas_id"
                                style="width: 345px"
                                placeholder="Pilih kelas"
                            >
                                <a-select-option
                                    v-for="cls in classes"
                                    :key="cls.id"
                                    :value="cls.id"
                                    >{{ cls.jenjang }} -
                                    {{ cls.section }}</a-select-option
                                >
                            </a-select>
                        </a-form-item>
                        <a-form-item
                            name="tanggal"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Pilih tanggal terlebih dahulu'
                                }
                            ]"
                        >
                            <a-date-picker
                                v-model:value="filter.tanggal"
                                style="width: 345px"
                                placeholder="Pilih tanggal"
                                value-format="YYYY-MM-DD"
                            />
                        </a-form-item>

                        <a-form-item>
                            <a-button
                                type="primary"
                                style="
                                    display: inline-flex;
                                    align-items: center;
                                "
                                html-type="submit"
                            >
                                <template #icon><SearchOutlined /></template>
                                Cari
                            </a-button>
                        </a-form-item>
                    </a-space>
                </a-form>

                <a-space
                    direction="horizontal"
                    style="display: flex; justify-content: flex-end"
                >
                    <a-button @click="cetakLaporanModalVisible = true">
                        <template #icon><PrinterOutlined /></template>
                        Cetak
                    </a-button>
                    <a-modal
                        v-model:visible="cetakLaporanModalVisible"
                        title="Cetak Laporan Kehadiran"
                        @ok="handleOk"
                    >
                        <p>Pilih berdasarkan:</p>
                        <a-select
                            style="width: 200px; margin-bottom: 30px"
                            placeholder="Pilih berdasarkan"
                            @change="handleSelectChange"
                        >
                            <a-select-option value="siswa"
                                >Siswa</a-select-option
                            >
                            <a-select-option value="kelas"
                                >Kelas</a-select-option
                            >
                        </a-select>

                        <a-form
                            :model="cetakLaporanForm"
                            :layout="'vertical'"
                            @finish="onFinishCetakLaporan"
                            ref="cetakLaporanForm"
                            v-if="selectedCetakLaporan == 'kelas'"
                        >
                            <a-form-item
                                name="tanggal"
                                label="Waktu :"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Pilih waktu terlebih dahulu'
                                    }
                                ]"
                            >
                                <a-date-picker
                                    placeholder="Pilih waktu"
                                    value-format="YYYY-MM"
                                    picker="month"
                                    style="width: 100%"
                                    v-model:value="cetakLaporanForm.tanggal"
                                />
                            </a-form-item>

                            <a-form-item
                                name="kelas"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Pilih kelas terlebih dahulu'
                                    }
                                ]"
                                label="Kelas :"
                            >
                                <a-select
                                    style="width: 100%"
                                    placeholder="Pilih kelas"
                                    v-model:value="cetakLaporanForm.kelas"
                                >
                                    <a-select-option
                                        v-for="cls in classes"
                                        :key="cls.id"
                                        :value="cls.id"
                                        >{{ cls.jenjang }} -
                                        {{ cls.section }}</a-select-option
                                    >
                                </a-select>
                            </a-form-item>
                            <a-form-item>
                                <a-button
                                    :loading="cetakLaporanButtonLoading"
                                    type="primary"
                                    html-type="submit"
                                >
                                    Cetak
                                </a-button>
                            </a-form-item>
                        </a-form>

                        <a-form
                            :model="cetakLaporanForm"
                            :layout="'vertical'"
                            @finish="onFinishCetakLaporan"
                            ref="cetakLaporanForm"
                            v-if="selectedCetakLaporan == 'siswa'"
                        >
                            <a-form-item
                                name="tanggal"
                                label="Waktu :"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Pilih waktu terlebih dahulu'
                                    }
                                ]"
                            >
                                <a-date-picker
                                    placeholder="Pilih waktu"
                                    value-format="YYYY-MM"
                                    picker="month"
                                    style="width: 100%"
                                    v-model:value="cetakLaporanForm.tanggal"
                                />
                            </a-form-item>

                            <a-form-item
                                name="kelas"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Pilih kelas terlebih dahulu'
                                    }
                                ]"
                                label="Kelas :"
                            >
                                <a-select
                                    style="width: 100%"
                                    placeholder="Pilih kelas"
                                    v-model:value="cetakLaporanForm.kelas"
                                    @change="cetakCariSiswaByKelas"
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
                                v-if="listSiswaInCetakLaporan.length > 0"
                                name="siswa"
                                label="Pilih siswa: "
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Pilih siswa terlebih dahulu'
                                    }
                                ]"
                            >
                                <a-select
                                    style="width=100%"
                                    placeholder="Pilih siswa"
                                    v-model:value="cetakLaporanForm.siswa"
                                >
                                    <a-select-option
                                        v-for="siswa in listSiswaInCetakLaporan"
                                        :key="siswa.id"
                                        :value="siswa.id"
                                        >{{ siswa.user.name }}</a-select-option
                                    >
                                </a-select>
                            </a-form-item>
                            <a-form-item>
                                <a-button
                                    :loading="cetakLaporanButtonLoading"
                                    type="primary"
                                    html-type="submit"
                                >
                                    Cetak
                                </a-button>
                            </a-form-item>
                        </a-form>
                    </a-modal>
                </a-space>
            </a-card>
            <a-card title="Daftar Siswa" style="width: 100%" v-if="dataReady">
                <a-form
                    :model="formKehadiran"
                    @finish="onFinishKehadiran"
                    @finishFailed="onFinishFailedKehadiran"
                    ref="formKehadiran"
                >
                    <a-space
                        direction="horizontal"
                        style="
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 20px;
                        "
                    >
                        <div style="align-items: center">
                            <h3 style="margin-left: 35px">
                                {{ dateInTable }}
                            </h3>
                        </div>

                        <div style="align-items: center">
                            <a-button type="primary" html-type="submit">
                                <template #icon><save-outlined /></template
                                >Simpan Kehadiran
                            </a-button>
                        </div>
                    </a-space>

                    <a-table
                        :columns="columns"
                        :data-source="dataSourceKehadiran"
                        style="margin: 15px"
                        :pagination="true"
                        :loading="dataSourceKehadiran.length === 0"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'id'">
                                <a>
                                    {{ record.key }}
                                </a>
                            </template>

                            <template v-if="column.key === 'nisn'">
                                <a>
                                    {{ record.nisn }}
                                </a>
                            </template>

                            <template v-if="column.key === 'nama'">
                                <a>
                                    {{ record.nama }}
                                </a>
                            </template>

                            <template v-else-if="column.key === 'kehadiran'">
                                <div>
                                    <a-form-item
                                        :name="[
                                            `siswa_${record.id}`,
                                            'kehadiran'
                                        ]"
                                        :rules="[
                                            {
                                                required: true,
                                                message:
                                                    'Pilih kehadiran terlebih dahulu'
                                            }
                                        ]"
                                    >
                                        <a-radio-group
                                            v-model:value="
                                                formKehadiran[
                                                    `siswa_${record.id}`
                                                ].kehadiran
                                            "
                                        >
                                            <a-radio value="hadir"
                                                >Hadir</a-radio
                                            >
                                            <a-radio value="absen"
                                                >Absen</a-radio
                                            >
                                            <a-radio value="telat"
                                                >Telat</a-radio
                                            >
                                            <a-radio value="izin">Izin</a-radio>
                                        </a-radio-group>
                                    </a-form-item>
                                </div>
                            </template>
                            <template v-else-if="column.key === 'keterangan'">
                                <a-form-item>
                                    <a-input
                                        v-model:value="
                                            formKehadiran[`siswa_${record.id}`]
                                                .keterangan
                                        "
                                        placeholder="Keterangan"
                                    />
                                </a-form-item>
                            </template>
                        </template>
                    </a-table>
                </a-form>
            </a-card>
        </a-col>
    </a-row>
</template>

<script>
import moment from 'moment'
import 'moment/locale/id'

const columns = [
    {
        title: 'ID',
        dataIndex: 'id',
        key: 'id'
    },
    {
        title: 'NISN',
        dataIndex: ['user', 'no_induk'],
        key: 'nisn'
    },
    {
        title: 'Nama Siswa',
        dataIndex: ['user', 'name'],
        key: 'nama'
    },
    {
        title: 'Kehadiran',
        dataIndex: 'kehadiran',
        key: 'kehadiran'
    },
    {
        title: 'Keterangan',
        dataIndex: 'keterangan',
        key: 'keterangan'
    }
]

export default {
    data() {
        return {
            dataSourceKehadiran: [],
            cetakLaporanModalVisible: false,
            selectedCetakLaporan: null,
            cetakLaporanForm: {
                tanggal: null
            },
            listSiswaInCetakLaporan: [],
            cetakLaporanButtonLoading: false,
            dateInTable: null,
            models: [],
            formKehadiran: {
                tanggal: null
            },
            columns,
            classes: [],
            filter: {
                kelas_id: null,
                tanggal: moment().format('YYYY-MM-DD')
            },
            dataReady: false
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

        readData() {},
        onFinishFilter() {
            this.readKehadiran()
        },
        onFinishKehadiran() {
            const tanggal = this.formKehadiran.tanggal
            const data = Object.values(this.formKehadiran)
            data.shift()

            this.axios
                .post(this.url('kehadiran/write'), {
                    model: data
                })
                .then((response) => {
                    console.log(response)
                    this.$message.success(response.data.message)
                    this.readKehadiran()
                })
                .catch((e) => {
                    this.$message.error(JSON.stringify(e.response.data.message))
                })
        },
        readKehadiran() {
            this.dataReady = true
            this.dataSourceKehadiran = []
            this.dateInTable = moment(this.filter.tanggal).format(
                'dddd, DD MMMM YYYY'
            )
            this.formKehadiran.tanggal = this.filter.tanggal
            this.axios
                .get(this.url('kehadiran/read'), {
                    params: {
                        req: 'by_kelas',
                        kelas_id: this.filter.kelas_id,
                        tanggal: this.filter.tanggal
                    }
                })
                .then((response) => {
                    this.models = response.data.models

                    this.models.forEach((model, index) => {
                        this.dataSourceKehadiran = [
                            ...this.dataSourceKehadiran,
                            {
                                key: index + 1,
                                id: model.user_id,
                                nisn: model.nisn,
                                nama: model.user.name
                            }
                        ]

                        this.formKehadiran = {
                            ...this.formKehadiran,
                            [`siswa_${model.user_id}`]: {
                                user_id: model.user_id,
                                kehadiran:
                                    Object.keys(model.kehadiran).length === 0
                                        ? null
                                        : model.kehadiran[0].kehadiran,
                                keterangan:
                                    Object.keys(model.kehadiran).length === 0
                                        ? null
                                        : model.kehadiran[0].keterangan,
                                tanggal: this.filter.tanggal
                            }
                        }
                    })

                    console.log(this.formKehadiran)
                })
        },
        handleSelectChange(value) {
            this.selectedCetakLaporan = value
            this.cetakLaporanForm = {
                tanggal: null
            }
        },
        cetakCariSiswaByKelas(value) {
            this.axios
                .get(this.url('siswa/read'), {
                    params: {
                        req: 'table',
                        kelas_id: value
                    }
                })
                .then((response) => {
                    this.listSiswaInCetakLaporan = response.data.models
                })
        },
        onFinishCetakLaporan() {
            this.cetakLaporanButtonLoading = true
            // this.cetakLaporanForm.year =
            //     this.cetakLaporanForm.tanggal.split('-')[0]
            // this.cetakLaporanForm.month =
            //     this.cetakLaporanForm.tanggal.split('-')[1]

            const cetakLaporanData = {
                month: this.cetakLaporanForm.tanggal.split('-')[1],
                year: this.cetakLaporanForm.tanggal.split('-')[0]
            }
            console.log(cetakLaporanData)

            if (this.selectedCetakLaporan === 'kelas') {
                cetakLaporanData.kelas = this.cetakLaporanForm.kelas
            } else if (this.selectedCetakLaporan === 'siswa') {
                cetakLaporanData.siswa = this.cetakLaporanForm.siswa
            }

            this.axios
                .get(
                    this.url('rekap/kehadiran'),
                    {
                        params: cetakLaporanData
                    },
                    {
                        responseType: 'blob'
                    }
                )
                .then((response) => {
                    this.cetakLaporanButtonLoading = false
                    this.cetakLaporanModalVisible = false
                    this.cetakLaporanForm = {
                        tanggal: null
                    }
                })
        }
    }
}
</script>
