<template>
    <h1 style="margin-left: 30px">Laporan Harian Siswa</h1>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-tabs v-model:activeKey="activeKey">
                <a-tab-pane key="1" tab="Tahfidz">
                    <a-card title="" style="width: 100%; margin-bottom: 20px">
                        <a-form
                            :model="formTahfidz"
                            @finish="onFinishTahfidz"
                            @finishFailed="onFinishFailed"
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
                        :loading="loading"
                    >
                        <a-space
                            direction="horizontal"
                            style="
                                display: flex;
                                justify-content: flex-end;
                                margin-bottom: 10px;
                            "
                        >
                        </a-space>

                        <a-table
                            :columns="columns"
                            :data-source="tahfizSource()"
                            style="margin: 15px"
                            :loading="loadingTahfidzData"
                        >
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
                    <a-form
                        :model="formIbadah"
                        @finish="onFinishIbadah"
                        @finishFailed="onFinishFailedIbadah"
                        ref="formIbadah"
                    >
                        <a-card style="width: 100%; margin-bottom: 20px">
                            <a-space>
                                <a-form-item
                                    label="Tanggal"
                                    name="tanggal"
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
                                        style="width: 345px"
                                        placeholder="Tanggal Posting"
                                        value-format="YYYY-MM-DD"
                                        v-model:value="formIbadah.tanggal"
                                    />
                                </a-form-item>

                                <a-form-item>
                                    <a-button type="primary" html-type="submit">
                                        Cari
                                    </a-button>
                                </a-form-item>
                            </a-space>
                        </a-card>
                    </a-form>
                    <a-card
                        title="Laporan Mutaba'ah Yaumiah"
                        style="width: 100%"
                        v-if="loadingFormIbadah"
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
                            :loading="loadingIbadahData"
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
                                        v-model:checked="record.value"
                                        disabled="true"
                                    ></a-checkbox>
                                </template>
                            </template>
                        </a-table>
                        <a-table
                            :dataSource="dataSource2"
                            :columns="kolom2"
                            :pagination="false"
                            :loading="loadingIbadahData"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'shalat'">
                                    <a>
                                        {{ record.shalat }}
                                    </a>
                                </template>
                                <template v-else-if="column.key === 'aksi'">
                                    <a-checkbox
                                        disabled="true"
                                        v-model:checked="record.value"
                                    ></a-checkbox>
                                </template>
                            </template>
                        </a-table>
                    </a-card>
                </a-tab-pane>

                <a-tab-pane key="3" tab="Perilaku Harian">
                    <a-form
                        :model="formPerilaku"
                        @finish="onFinishPerilaku"
                        @finishFailed="onFinishFailed"
                        ref="formPerilaku"
                    >
                        <a-card
                            title="Tanggal Kegiatan"
                            style="width: 100%; margin-bottom: 20px"
                        >
                            <a-space>
                                <a-form-item
                                    name="tanggal"
                                    :rules="[
                                        {
                                            required: true,
                                            message: 'Tanggal harus diisi'
                                        },
                                        {
                                            pattern: new RegExp(
                                                '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                            ),
                                            message:
                                                'Format tanggal harus YYYY-MM-DD'
                                        }
                                    ]"
                                >
                                    <a-date-picker
                                        v-model:value="formPerilaku.tanggal"
                                        style="width: 345px"
                                        placeholder="Tanggal"
                                        value-format="YYYY-MM-DD"
                                    />
                                </a-form-item>

                                <a-form-item>
                                    <a-button type="primary" html-type="submit">
                                        Cari
                                    </a-button>
                                </a-form-item>
                            </a-space>
                        </a-card>
                    </a-form>
                    <a-card
                        v-if="loadingFormPerilaku"
                        title="Laporan Perilaku"
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
                            :dataSource="dataSourceIbadah"
                            :columns="columnIbadah"
                            :pagination="false"
                            style="margin-bottom: 6px"
                            bordered
                            :loading="loadingPerilakuData"
                        >
                            <template #bodyCell="{ column, record }">
                                <div
                                    v-if="
                                        record.list_perilaku.type === 'ibadah'
                                    "
                                >
                                    <template v-if="column.key === 'ibadah'">
                                        <a>
                                            {{ record.list_perilaku.name }}
                                        </a>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'point'"
                                    >
                                        {{ record.nilai }}
                                    </template>
                                    <template
                                        v-else-if="column.key === 'catatan'"
                                    >
                                        {{ record.catatan ?? '-' }}
                                    </template>
                                </div>
                            </template>
                        </a-table>

                        <a-table
                            :dataSource="dataSourceAkidah"
                            :columns="columnAkidah"
                            :pagination="false"
                            style="margin-bottom: 6px"
                            bordered
                            :loading="loadingPerilakuData"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'akidah'">
                                    <a>
                                        {{ record.list_perilaku.name }}
                                    </a>
                                </template>
                                <template v-else-if="column.key === 'point'">
                                    {{ record.nilai }}
                                </template>
                                <template v-else-if="column.key === 'catatan'">
                                    {{ record.catatan ?? '-' }}
                                </template>
                            </template>
                        </a-table>

                        <a-table
                            :dataSource="dataSourceAkhlak"
                            :columns="columnAkhlak"
                            :pagination="false"
                            bordered
                            :loading="loadingPerilakuData"
                            style="margin-bottom: 6px"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'akhlak'">
                                    <a>
                                        {{ record.list_perilaku.name }}
                                    </a>
                                </template>
                                <template v-else-if="column.key === 'point'">
                                    {{ record.nilai }}
                                </template>
                                <template v-else-if="column.key === 'catatan'">
                                    {{ record.catatan ?? '-' }}
                                </template>
                            </template>
                        </a-table>

                        <a-table
                            :dataSource="dataSourceKedisplinan"
                            :columns="columnKedisplinan"
                            :pagination="false"
                            bordered
                            :loading="loadingPerilakuData"
                            style="margin-bottom: 6px"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'kedisplinan'">
                                    <a>
                                        {{ record.list_perilaku.name }}
                                    </a>
                                </template>
                                <template v-else-if="column.key === 'point'">
                                    {{ record.nilai }}
                                </template>
                                <template v-else-if="column.key === 'catatan'">
                                    {{ record.catatan ?? '-' }}
                                </template>
                            </template>
                        </a-table>

                        <a-table
                            :dataSource="dataSourceKerapian"
                            :columns="columnKerapian"
                            :pagination="false"
                            bordered
                            :loading="loadingPerilakuData"
                            style="margin-bottom: 6px"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'kerapian'">
                                    <a>
                                        {{ record.list_perilaku.name }}
                                    </a>
                                </template>
                                <template v-else-if="column.key === 'point'">
                                    {{ record.nilai }}
                                </template>
                                <template v-else-if="column.key === 'catatan'">
                                    {{ record.catatan ?? '-' }}
                                </template>
                            </template>
                        </a-table>
                    </a-card>
                </a-tab-pane>
            </a-tabs>
        </a-col>
    </a-row>
</template>

<script>
import { message } from 'ant-design-vue'
import {
    SearchOutlined,
    DownloadOutlined,
    UploadOutlined,
    SaveOutlined,
    ConsoleSqlOutlined
} from '@ant-design/icons-vue'

import moment from 'moment'
import { tuple } from 'ant-design-vue/lib/_util/type'

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
        key: 'tanggal',
        width: 150
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

const columnIbadah = [
    {
        title: 'Ibadah',
        dataIndex: 'ibadah',
        key: 'ibadah'
    },
    {
        title: 'Poin',
        key: 'point'
    },
    {
        title: 'Catatan',
        key: 'catatan'
    }
]

const columnAkidah = [
    {
        title: 'Aqidah',
        dataIndex: 'aqidah',
        key: 'aqidah'
    },
    {
        title: 'Poin',
        key: 'point'
    },
    {
        title: 'Catatan',
        key: 'catatan'
    }
]

export default {
    data() {
        return {
            columns,
            dataSource1: null,
            kolom1,
            dataSource2: null,
            kolom2,
            dataSourceIbadah: null,
            columnIbadah: this.columnPerilaku('ibadah'),
            dataSourceAkidah: null,
            columnAkidah: this.columnPerilaku('akidah'),
            dataSourceAkhlak: null,
            columnAkhlak: this.columnPerilaku('akhlak'),
            dataSourceKedisplinan: null,
            columnKedisplinan: this.columnPerilaku('kedisplinan'),
            dataSourceKerapian: null,
            columnKerapian: this.columnPerilaku('kerapian'),

            classes: [],
            formTahfidz: {
                startDate: moment().format('YYYY-MM-DD'),
                endDate: moment().format('YYYY-MM-DD')
            },
            formIbadah: {
                tanggal: moment().format('YYYY-MM-DD')
            },
            formPerilaku: {
                tanggal: moment().format('YYYY-MM-DD')
            },
            ibadahData: null,
            loadingFormIbadah: false,
            loadingIbadahData: true,
            loading: true,
            tahfidzData: null,
            perilakuData: null,
            loadingFormPerilaku: false,
            loadingPerilakuData: true,
            loadingTahfidzData: true,
            userData: null
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
            this.loading = false
            this.loadingTahfidzData = true
            this.searchTahfidz()
        },
        onFinishIbadah() {
            this.loadingFormIbadah = true
            this.loadingIbadahData = true
            this.ibadahData = null
            console.log(this.ibadahData)

            this.searchIbadah()
        },
        onFinishPerilaku() {
            this.loadingFormPerilaku = true
            this.loadingPerilakuData = true
            this.perilakuData = null
            this.searchPerilaku()
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
                    this.loadingTahfidzData = false
                })
                .catch((e) => {
                    this.loadingTahfidzData = false
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: e.response.data.message
                    })
                })
        },
        searchIbadah() {
            this.axios
                .get(this.url('laporan/mutabaah-yaumiyah/read'), {
                    params: {
                        user_id: this.userData.id,
                        req: 'get_mutabaahyaumiyah_by_user_id_and_date',
                        ...this.formIbadah
                    }
                })
                .then((res) => {
                    this.ibadahData = res.data.data

                    this.loadingIbadahData = false

                    this.dataSource1 = [
                        {
                            key: '1',
                            kegiatanibadah: 'Qiyamul Lail',
                            value: this.ibadahData?.qiyamul_lail
                        },
                        {
                            key: '2',
                            kegiatanibadah: 'Dhuha',
                            value: this.ibadahData?.dhuha
                        },
                        {
                            key: '3',
                            kegiatanibadah: 'Tilawah Quran',
                            value: this.ibadahData?.tilawah_quran
                        },
                        {
                            key: '4',
                            kegiatanibadah: 'Membaca Buku',
                            value: this.ibadahData?.membaca_buku
                        },
                        {
                            key: '5',
                            kegiatanibadah: 'Olahraga',
                            value: this.ibadahData?.olahraga
                        },
                        {
                            key: '6',
                            kegiatanibadah: 'Al Matsurat',
                            value: this.ibadahData?.al_matsurat
                        },
                        {
                            key: '7',
                            kegiatanibadah: 'Shoum Sunnah',
                            value: this.ibadahData?.shoum_sunnah
                        }
                    ]

                    this.dataSource2 = [
                        {
                            key: '1',
                            shalat: 'Shalat Subuh',
                            value: this.ibadahData?.shalat_subuh
                        },
                        {
                            key: '2',
                            shalat: 'Shalat Dzuhur',
                            value: this.ibadahData?.shalat_dzuhur
                        },
                        {
                            key: '3',
                            shalat: 'Shalat Ashar',
                            value: this.ibadahData?.shalat_ashar
                        },
                        {
                            key: '4',
                            shalat: 'Shalat Maghrib',
                            value: this.ibadahData?.shalat_maghrib
                        },
                        {
                            key: '5',
                            shalat: 'Shalat Isya',
                            value: this.ibadahData?.shalat_isya
                        }
                    ]
                })
                .catch((e) => {
                    this.loadingIbadahData = false
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: e.res.data.message
                    })
                })
        },
        searchPerilaku() {
            this.axios
                .get(this.url('laporan/perilaku/data'), {
                    params: {
                        user_id: this.userData.id,
                        req: 'get_perilaku_by_user_id_and_date',
                        ...this.formPerilaku
                    }
                })
                .then((res) => {
                    this.perilakuData = res.data.data

                    this.loadingPerilakuData = false

                    console.log(this.perilakuData)

                    this.dataSourceIbadah = this.perilakuData.filter(
                        (item, index) => {
                            return item.list_perilaku.type === 'ibadah'
                        }
                    )

                    this.dataSourceAkhlak = this.perilakuData.filter(
                        (item, index) => {
                            return item.list_perilaku.type === 'akhlak'
                        }
                    )

                    console.log(this.dataSourceAkhlak)

                    this.dataSourceAkidah = this.perilakuData.filter(
                        (item, index) => {
                            return item.list_perilaku.type === 'akidah'
                        }
                    )

                    this.dataSourceKedisplinan = this.perilakuData.filter(
                        (item, index) => {
                            return item.list_perilaku.type === 'kedisiplinan'
                        }
                    )
                })
                .catch((e) => {
                    this.loadingPerilakuData = false
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: e.res.data.message
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

                this.axios.get(this.url('perilaku/data')).then((res) => {
                    const response = res.data.data

                    this.dataSourceIbadah = response.filter((item, index) => {
                        return item.type === 'ibadah'
                    })

                    this.dataSourceAkhlak = response.filter((item, index) => {
                        return item.type === 'akhlak'
                    })

                    this.dataSourceAkidah = response.filter((item, index) => {
                        return item.type === 'akidah'
                    })

                    this.dataSourceKedisplinan = response.filter(
                        (item, index) => {
                            return item.type === 'kedisiplinan'
                        }
                    )

                    this.dataSourceKerapian = response.filter((item, index) => {
                        return item.type === 'kebersihandankerapian'
                    })

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
            ;(this.formTahfidz.tanggal = null),
                (this.formTahfidz.surah = 'Al-Fatihah'),
                (this.formTahfidz.ayat = null)
            this.formTahfidz.tanggal = moment().format('YYYY-MM-DD')
        },
        tahfizSource() {
            return this.tahfidzData.map((item, index) => {
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
