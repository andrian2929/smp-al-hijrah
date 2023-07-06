<template>
    <h1 style="margin-left: 30px">Laporan Harian Guru</h1>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-tabs v-model:activeKey="activeKey">
                <a-tab-pane key="1" tab="Tahfidz">
                    <a-card title="" style="width: 100%; margin-bottom: 20px">
                        <a-form
                            :model="formTahfidz"
                            @finish="onFinishTahfidz"
                            ref="formTahfidz"
                        >
                            <a-space
                                style="
                                    display: flex;
                                    justify-content: flex-container;
                                    flex-wrap: wrap;
                                "
                            >
                                <a-form-item
                                    name="startDate"
                                    label="Dari"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Tanggal tidak boleh kosong'
                                        },
                                        {
                                            pattern: new RegExp(
                                                '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                            ),
                                            message:
                                                'Format tanggal lahir harus YYYY-MM-DD'
                                        }
                                    ]"
                                >
                                    <a-date-picker
                                        v-model:value="formTahfidz.startDate"
                                        style="width: 200px"
                                        placeholder="Tanggal Posting"
                                        value-format="YYYY-MM-DD"
                                    />
                                </a-form-item>

                                <a-form-item
                                    name="endDate"
                                    label="Sampai"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Tanggal tidak boleh kosong'
                                        },
                                        {
                                            pattern: new RegExp(
                                                '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                            ),
                                            message:
                                                'Format tanggal lahir harus YYYY-MM-DD'
                                        }
                                    ]"
                                >
                                    <a-date-picker
                                        v-model:value="formTahfidz.endDate"
                                        style="width: 200px"
                                        placeholder="Tanggal Posting"
                                        value-format="YYYY-MM-DD"
                                    />
                                </a-form-item>

                                <a-form-item>
                                    <a-button
                                        type="primary"
                                        html-type="submit"
                                        style="
                                            display: inline-flex;
                                            align-items: center;
                                        "
                                    >
                                        Cari
                                    </a-button>
                                </a-form-item>
                            </a-space>
                        </a-form>
                    </a-card>
                    <a-card
                        title="Laporan Tahfidz"
                        style="width: 100%"
                        v-if="showTahfidzData"
                    >
                        <a-space
                            direction="horizontal"
                            style="display: flex; justify-content: flex-end"
                        >
                        </a-space>

                        <a-table :columns="columns" :data-source="tahfidzData">
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'tanggal'">
                                    <a>
                                        {{ record.tanggal }}
                                    </a>
                                </template>
                            </template>
                        </a-table>
                    </a-card>
                </a-tab-pane>

                <a-tab-pane key="2" tab="Ibadah Harian" force-render>
                    <a-card
                        :loading="loading"
                        title="Tanggal Kegiatan"
                        style="width: 100%; margin-bottom: 20px"
                    >
                        <a-date-picker
                            style="width: 345px"
                            placeholder="Tanggal Posting"
                            value-format="YYYY-MM-DD"
                            v-model:value="formIbadah.Tanggal"
                        />
                    </a-card>
                    <a-card
                        :loading="loading"
                        title="Laporan Mutaba'ah Yaumiah"
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
                        </a-space>

                        <a-table
                            :dataSource="dataSource1"
                            :columns="kolom1"
                            :pagination="false"
                        >
                            <template #bodyCell="{ column, record }">
                                <template
                                    v-if="column.key === 'kegiatanibadah'"
                                >
                                    <a>
                                        {{ record.kegiatanibadah }}
                                    </a>
                                </template>
                                <template v-else-if="column.key === 'aksi'">
                                    <a-checkbox
                                        v-model:checked="
                                            formIbadah[
                                                `${record.kegiatanibadah.replace(
                                                    ' ',
                                                    ''
                                                )}`
                                            ]
                                        "
                                    ></a-checkbox>
                                </template>
                            </template>
                        </a-table>
                        <a-table
                            :dataSource="dataSource2"
                            :columns="kolom2"
                            :pagination="false"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'shalat'">
                                    <a>
                                        {{ record.shalat }}
                                    </a>
                                </template>
                                <template v-else-if="column.key === 'aksi'">
                                    <a-checkbox
                                        v-model:checked="
                                            formIbadah[
                                                `${record.shalat.replace(
                                                    ' ',
                                                    ''
                                                )}`
                                            ]
                                        "
                                    ></a-checkbox>
                                </template>
                            </template>
                        </a-table>

                        <a-button
                            type="primary"
                            style="display: inline-flex; align-items: center"
                            @click="saveMutabaahYaumiah"
                        >
                            <template #icon><save-outlined /></template>Simpan
                        </a-button>
                    </a-card>
                </a-tab-pane>

                <a-tab-pane key="3" tab="Perilaku Harian">
                    <a-card title="Tanggal Kegiatan">
                        <a-form
                            :model="formPerilaku"
                            @finish="onFinishPerilaku"
                            ref="formPerilaku"
                        >
                            <a-space
                                style="
                                    display: flex;
                                    justify-content: flex-container;
                                    flex-wrap: wrap;
                                "
                            >
                                <a-form-item
                                    name="startDate"
                                    label="Dari"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Tanggal tidak boleh kosong'
                                        },
                                        {
                                            pattern: new RegExp(
                                                '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                            ),
                                            message:
                                                'Format tanggal lahir harus YYYY-MM-DD'
                                        }
                                    ]"
                                >
                                    <a-date-picker
                                        v-model:value="formPerilaku.startDate"
                                        style="width: 200px"
                                        placeholder="Tanggal Posting"
                                        value-format="YYYY-MM-DD"
                                    />
                                </a-form-item>

                                <a-form-item
                                    name="endDate"
                                    label="Sampai"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Tanggal tidak boleh kosong'
                                        },
                                        {
                                            pattern: new RegExp(
                                                '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                            ),
                                            message:
                                                'Format tanggal lahir harus YYYY-MM-DD'
                                        }
                                    ]"
                                >
                                    <a-date-picker
                                        v-model:value="formPerilaku.endDate"
                                        style="width: 200px"
                                        placeholder="Tanggal Posting"
                                        value-format="YYYY-MM-DD"
                                    />
                                </a-form-item>

                                <a-form-item>
                                    <a-button
                                        type="primary"
                                        html-type="submit"
                                        style="
                                            display: inline-flex;
                                            align-items: center;
                                        "
                                    >
                                        Cari
                                    </a-button>
                                </a-form-item>
                            </a-space>
                        </a-form>
                    </a-card>

                    <a-card title="Laporan Perilaku" v-if="perilakuData">
                        <a-table
                            :columns="perilakuColumns"
                            :dataSource="perilakuData"
                        >
                            <template #bodyCell="{ column, record }">
                            </template>
                        </a-table>
                    </a-card>
                </a-tab-pane>
            </a-tabs>
        </a-col>
    </a-row>
</template>

<script>
const dataSource1 = [
    {
        key: '1',
        kegiatanibadah: 'Qiyamul Lail'
    },
    {
        key: '2',
        kegiatanibadah: 'Dhuha'
    },
    {
        key: '3',
        kegiatanibadah: 'Tilawah Quran'
    },
    {
        key: '4',
        kegiatanibadah: 'Membaca Buku'
    },
    {
        key: '5',
        kegiatanibadah: 'Olahraga'
    },
    {
        key: '6',
        kegiatanibadah: 'Al Matsurat'
    },
    {
        key: '7',
        kegiatanibadah: 'Shoum Sunnah'
    }
]

const dataSource2 = [
    {
        key: '1',
        shalat: 'Shalat Subuh'
    },
    {
        key: '2',
        shalat: 'Shalat Dzuhur'
    },
    {
        key: '3',
        shalat: 'Shalat Ashar'
    },
    {
        key: '4',
        shalat: 'Shalat Maghrib'
    },
    {
        key: '5',
        shalat: 'Shalat Isya'
    }
]

import { message } from 'ant-design-vue'
import {
    SearchOutlined,
    DownloadOutlined,
    UploadOutlined,
    SaveOutlined,
    ConsoleSqlOutlined
} from '@ant-design/icons-vue'

import moment from 'moment'
const columns = [
    {
        title: 'No.',
        dataIndex: 'key',
        key: 'no',
        width: 50
    },
    {
        title: 'Tanggal',
        dataIndex: 'Tanggal',
        key: 'tanggal'
    },
    {
        title: 'Surah',
        dataIndex: 'surah',
        key: 'surah'
    },
    {
        title: 'Ayat',
        dataIndex: 'ayat',
        key: 'ayat'
    }
]

const kolom1 = [
    {
        title: 'Kegiatan',
        dataIndex: 'kegiatanibadah',
        key: 'kegiatanibadah'
    },
    {
        title: 'Aksi',
        key: 'aksi'
    }
]

const kolom2 = [
    {
        title: 'Shalat',
        dataIndex: 'shalat',
        key: 'shalat'
    },
    {
        title: 'Aksi',
        key: 'aksi'
    }
]

const perilakuColumns = [
    {
        title: 'No.',
        dataIndex: 'no',
        key: 'no'
    },
    {
        title: 'Tanggal',
        dataIndex: 'tanggal',
        key: 'tanggal',
        width: '25%'
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
            columns,
            dataSource1,
            kolom1,
            dataSource2,
            kolom2,
            dataSourceIbadah: null,
            columnIbadah: this.columnPerilaku('ibadah'),
            dataSourceAkidah: null,
            perilakuColumns,
            filterTahfizh: {
                req: 'single_by_date',
                startDate: null,
                endDate: null
            },
            filterPerilaku: {
                guru_id: null,
                startDate: null,
                endDate: null
            },
            perilakuData: null,
            showTahfidzData: false,
            classes: [],
            formTahfidz: {
                startDate: moment().format('YYYY-MM-DD'),
                endDate: moment().format('YYYY-MM-DD')
            },
            loadingTahfidzData: true,
            userData: null,
            loading: true,
            tahfidzData: null,
            formIbadah: {
                QiyamulLail: false,
                Dhuha: false,
                TilawahQuran: false,
                MembacaBuku: false,
                Olahraga: false,
                AlMatsurat: false,
                ShoumSunnah: false,
                ShalatSubuh: false,
                ShalatDzuhur: false,
                ShalatAshar: false,
                ShalatMaghrib: false,
                ShalatIsya: false,
                Tanggal: moment().format('YYYY-MM-DD')
            },
            formPerilaku: {
                startDate: moment().format('YYYY-MM-DD'),
                endDate: moment().format('YYYY-MM-DD')
            }
        }
    },
    created() {
        this.readData()
        this.readLaporanTahfidz()
    },
    computed: {},
    components: {
        SearchOutlined,
        DownloadOutlined,
        UploadOutlined,
        SaveOutlined
    },
    methods: {
        onFinishTahfidz() {
            this.readTahfidz()
        },
        searchTahfidz() {
            this.axios
                .get(this.url('laporan/tahfidz/read'), {
                    params: {
                        user_id: this.userData.id,
                        req: 'single_by_date',
                        ...this.formTahfidz
                    }
                })
                .then((res) => {
                    this.tahfidzData = res.data.data
                    console.log(res)
                })
                .catch((e) => {
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: e.response.data.message
                    })
                })
        },
        onFinishPerilaku() {
            this.readPerilaku()
        },
        readTahfidz() {
            this.filterTahfizh.startDate = this.formTahfidz.startDate
            this.filterTahfizh.endDate = this.formTahfidz.endDate
            this.axios
                .get(this.url('user'))
                .then((res) => {
                    this.filterTahfizh.user_id = res.data.id
                    const params = this.filterTahfizh
                    this.axios
                        .get(this.url('laporan/tahfidz/read'), { params })
                        .then((res) => {
                            this.tahfidzData = res.data.data.map(
                                (item, index) => {
                                    return {
                                        key: index + 1,
                                        tanggal: moment(item.tanggal).format(
                                            'dddd, DD MMMM YYYY'
                                        ),
                                        surah: item.surah,
                                        ayat:
                                            item.ayat_start +
                                            ' - ' +
                                            item.ayat_end
                                    }
                                }
                            )

                            this.showTahfidzData = true
                        })
                        .catch((err) => {
                            if (err.response.status === 422) {
                                this.$notification.error({
                                    message: 'Kesalahan',
                                    description: err.response.data.message
                                })
                            }
                        })
                })
                .catch((err) => {})
        },
        readPerilaku() {
            this.filterPerilaku.startDate = this.formPerilaku.startDate
            this.filterPerilaku.endDate = this.formPerilaku.endDate
            this.axios
                .get(this.url('user'))
                .then((res) => {
                    this.filterPerilaku.user_id = res.data.id
                    const params = this.filterPerilaku

                    this.axios
                        .get(this.url('piket/jurnal/guru/ketertiban/'), {
                            params
                        })
                        .then((res) => {
                            this.perilakuData = res.data.data.map(
                                (item, index) => {
                                    return {
                                        no: index + 1,
                                        tanggal: moment(item.tanggal).format(
                                            'dddd, DD MMMM YYYY'
                                        ),
                                        keterangan: item.keterangan
                                    }
                                }
                            )

                            console.log(this.perilakuData)
                        })
                })
                .catch((err) => {})
        },

        saveLaporanTahfidz() {
            this.axios
                .post(
                    this.url('laporan/tahfidz/write'),
                    (this.formTahfidz = {
                        ...this.formTahfidz,
                        user_id: this.userData.id
                    })
                )
                .then((res) => {
                    this.readData()
                    this.resetFormTahfidz()
                    this.$notification.success({
                        message: 'Berhasil',
                        description: res.data.message
                    })
                })
                .catch((e) => {
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: JSON.stringify(e.response.data.errors)
                    })
                })
        },
        readData() {
            this.axios.get(this.url('user')).then((res) => {
                this.userData = res.data
                const params = {
                    user_id: this.userData.id,
                    req: 'single'
                }
                this.axios
                    .get(this.url('laporan/tahfidz/read'), { params })
                    .then((res) => {
                        this.tahfidzData = res.data
                    })

                this.axios
                    .get(this.url('laporan/perilaku/data'))
                    .then((res) => {
                        const response = res.data.data

                        this.dataSourceIbadah = response.filter(
                            (item, index) => {
                                return item.type === 'ibadah'
                            }
                        )

                        this.dataSourceAkhlak = response.filter(
                            (item, index) => {
                                return item.type === 'akhlak'
                            }
                        )

                        this.dataSourceAkidah = response.filter(
                            (item, index) => {
                                return item.type === 'akidah'
                            }
                        )

                        this.dataSourceKedisplinan = response.filter(
                            (item, index) => {
                                return item.type === 'kedisiplinan'
                            }
                        )

                        this.dataSourceKerapian = response.filter(
                            (item, index) => {
                                return item.type === 'kebersihandankerapian'
                            }
                        )

                        response.forEach((item, index) => {
                            this.formPerilaku = {
                                ...this.formPerilaku,
                                [item.name.replace(/\s+/g, '')]: {
                                    perilaku_id: item.id,
                                    user_id: this.userData.id,
                                    nilai: null,
                                    catatan: null
                                }
                            }
                        })

                        this.loading = false
                    })
            })
        },
        resetFormTahfidz() {
            this.formTahfidz.tanggal = null
            this.formTahfidz.surah = 'Al-Fatihah'
            this.formTahfidz.ayat = null
            this.formTahfidz.tanggal = moment().format('YYYY-MM-DD')
        },
        tahfizSource() {
            return this.tahfidzData.data.map((item, index) => {
                return {
                    key: index + 1,
                    tanggal: moment(item.tanggal).format('dddd, DD MMMM YYYY'),
                    surah: item.surah,
                    ayat: item.ayat_start + ' - ' + item.ayat_end
                }
            })
        },
        resetFormIbadah() {
            this.formIbadah.QiyamulLail = false
            this.formIbadah.Dhuha = false
            this.formIbadah.TilawahQuran = false
            this.formIbadah.MembacaBuku = false
            this.formIbadah.Olahraga = false
            this.formIbadah.AlMatsurat = false
            this.formIbadah.ShoumSunnah = false
            this.formIbadah.ShalatSubuh = false
            this.formIbadah.ShalatDzuhur = false
            this.formIbadah.ShalatAshar = false
            this.formIbadah.ShalatMaghrib = false
            this.formIbadah.ShalatIsya = false
            this.formIbadah.Tanggal = moment().format('YYYY-MM-DD')
        },
        saveMutabaahYaumiah() {
            this.formIbadah = { ...this.formIbadah, user_id: this.userData.id }
            this.axios
                .post(
                    this.url('laporan/mutabaah-yaumiyah/write'),
                    this.formIbadah
                )
                .then((res) => {
                    this.$notification.success({
                        message: 'Berhasil',
                        description: res.data.message
                    })

                    window.scrollTo(0, 0)
                    this.resetFormIbadah()
                })
                .catch((e) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: JSON.stringify(e.response.data.errors)
                    })
                })
        },
        columnPerilaku(title) {
            return [
                {
                    title: `${title.charAt(0).toUpperCase() + title.slice(1)}`,
                    dataIndex: title,
                    key: title,
                    width: '50%'
                },
                {
                    title: 'Poin',
                    key: 'point',
                    width: '25%'
                },
                {
                    title: 'Catatan',
                    key: 'catatan',
                    width: '25%'
                }
            ]
        }
    }
}
</script>
