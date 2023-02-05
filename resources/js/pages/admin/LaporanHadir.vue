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
                        :pagination="false"
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
        }
    }
}
</script>
