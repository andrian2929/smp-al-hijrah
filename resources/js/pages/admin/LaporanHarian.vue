<template>
    <h1 style="margin-left: 30px">Laporan Harian Siswa</h1>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
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
                            margin-bottom: 20px;
                        "
                    >
                        <a-form-item
                            name="class"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Pilih kelas terlebih dahulu',
                                    trigger: 'blur'
                                }
                            ]"
                        >
                            <a-select
                                v-model:value="filter.class"
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
                                    message: 'Pilih tanggal terlebih dahulu',
                                    trigger: 'blur'
                                }
                            ]"
                        >
                            <a-date-picker
                                v-model:value="filter.tanggal"
                                style="width: 345px"
                                placeholder="Tanggal Dari"
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
            </a-card>
            <a-card v-if="dataReady" title="Daftar Siswa" style="width: 100%">
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
                        placeholder="Cari siswa"
                        style="width: 200px"
                        @search="readData"
                    />
                    <a-button>
                        <template #icon><download-outlined /></template>
                    </a-button>
                </a-space>
                <a-table
                    :columns="columns"
                    :data-source="DataSourceSiswa"
                    style="margin: 15px"
                    :loading="DataSourceSiswa.length === 0"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'nama'">
                            <a>
                                {{ record.nama }}
                            </a>
                        </template>
                        <template v-else-if="column.key === 'action'">
                            <a-button
                                type="primary"
                                @click="loadFormTahfiz(record.id)"
                                style="margin-bottom: 4px; margin-right: 6px"
                                >Tahfidz</a-button
                            >
                            <a-button
                                type="primary"
                                @click="loadFormMutabaahYaumiyah(record.id)"
                                style="margin-bottom: 4px; margin-right: 6px"
                                >Ibadah Harian</a-button
                            >
                            <a-button
                                type="primary"
                                @click="loadFormPerilakuData(record.id)"
                                >Perilaku Harian</a-button
                            >
                        </template>
                    </template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>
    <a-modal v-model:visible="showModal1" title="Posting Laporan Tahfidz">
        <template #footer>
            <a-button key="back" @click="handleCancel">Return</a-button>
            <a-button key="submit" type="primary" @click="handleOk"
                >Submit</a-button
            >
        </template>

        <a-spin :spinning="loadingFormTahfiz">
            <div
                style="margin-bottom: 20px; max-width: 100%"
                v-if="formTahfidz.length != []"
            >
                <a-button
                    shape="round"
                    size="small"
                    @click="loadFormTahfizSingle()"
                    :type="selectedTahfidz == null ? 'primary' : 'default'"
                    style="margin: 3px"
                >
                    Tambah
                </a-button>

                <a-button
                    v-for="item in formTahfidz"
                    :key="item.id"
                    shape="round"
                    size="small"
                    @click="loadFormTahfizSingle(item.id)"
                    :type="selectedTahfidz == item.id ? 'primary' : 'default'"
                    style="margin: 3px"
                >
                    {{ item.surah }}
                </a-button>
            </div>

            <a-form
                :model="formTahfidzSingle"
                @finish="onFinishTahfidzSingle"
                @finishFailed="onFinishFailed"
                ref="formTahfidzSingle"
            >
                <p>Nama Surah</p>
                <a-form-item>
                    <a-select
                        v-model:value="formTahfidzSingle.surah"
                        style="max-width;: 345px"
                        placeholder="Nama Surah"
                    >
                        <a-select-option
                            v-for="_surah in surah"
                            :key="_surah.key"
                            :value="_surah.surah"
                        >
                            {{ _surah.surah }}
                        </a-select-option>
                    </a-select>
                </a-form-item>

                <p>Ayat Surah</p>
                <a-form-item
                    :rules="[
                        {
                            required: true,
                            message: 'Keterangan ayat surah tidak boleh kosong',
                            trigger: 'blur'
                        },
                        {
                            pattern: /^[0-9]+-[0-9]+$/,
                            message: 'Format ayat surah salah'
                        }
                    ]"
                    name="ayat"
                >
                    <a-input
                        placeholder="Contoh : 1-5"
                        style="max-width;: 345px"
                        v-model:value="formTahfidzSingle.ayat"
                    />
                </a-form-item>
                <a-form-item>
                    <a-space>
                        <a-button type="primary" html-type="submit">
                            Simpan
                        </a-button>
                        <a-button
                            type="danger"
                            @click="alert(selectedTahfidz, deleteTahfidz)"
                            v-if="selectedTahfidz != null"
                        >
                            Hapus
                        </a-button>
                    </a-space>
                </a-form-item>
            </a-form>
        </a-spin>
    </a-modal>
    <!-- modal bagian ibadah harian -->
    <a-modal
        v-model:visible="showModal2"
        title="Posting Mutaba'ah Ibadah"
        @ok="saveMutabaahYaumiah"
    >
        <a-table
            :dataSource="dataSource1"
            :columns="kolom1"
            :pagination="false"
            :loading="loadingFormMutabaahYaumiyah"
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'kegiatanibadah'">
                    <a>
                        {{ record.kegiatanibadah }}
                    </a>
                </template>
                <template v-else-if="column.key === 'aksi'">
                    <a-checkbox
                        v-model:checked="
                            formMutabaahYaumiyah[
                                `${record.kegiatanibadah.replace(' ', '')}`
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
            :loading="loadingFormMutabaahYaumiyah"
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
                            formMutabaahYaumiyah[
                                `${record.shalat.replace(' ', '')}`
                            ]
                        "
                    ></a-checkbox>
                </template>
            </template>
        </a-table>
    </a-modal>

    <a-modal
        v-model:visible="showModal3"
        title="Posting Laporan Perilaku Harian Siswa"
        width="1000px"
    >
        <a-form
            :model="formPerilaku"
            @finish="onFinishPerilaku"
            @finishFailed="onFinishFailed"
            ref="formPerilaku"
        >
            <template #footer>
                <a-button key="back" @click="showModal3 = false"
                    >Cancel</a-button
                >
            </template>
            <a-table
                :dataSource="dataSourceIbadah"
                :columns="columnIbadah"
                :pagination="false"
                style="margin-bottom: 6px"
                :loading="loadingFormPerilaku"
                bordered
            >
                <template #bodyCell="{ column, record }">
                    <div v-if="record.type === 'ibadah'">
                        <template v-if="column.key === 'ibadah'">
                            <a>
                                {{ record.name }}
                            </a>
                        </template>
                        <template v-else-if="column.key === 'point'">
                            <a-form-item
                                :name="[
                                    record.name.replace(/\s+/g, ''),
                                    'nilai'
                                ]"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Poin tidak boleh kosong',
                                        trigger: 'blur'
                                    },
                                    {
                                        pattern: new RegExp('^[0-9]*$'),
                                        message: 'Poin harus berupa angka',
                                        trigger: 'blur'
                                    }
                                ]"
                            >
                                <a-input
                                    v-model:value="
                                        formPerilaku[
                                            record.name.replace(/\s+/g, '')
                                        ].nilai
                                    "
                                    placeholder="Masukkan poin"
                                />
                            </a-form-item>
                        </template>
                        <template v-else-if="column.key === 'catatan'">
                            <a-form-item
                                :name="[
                                    record.name.replace(/\s+/g, ''),
                                    'catatan'
                                ]"
                            >
                                <a-input
                                    v-model:value="
                                        formPerilaku[
                                            record.name.replace(/\s+/g, '')
                                        ].catatan
                                    "
                                    placeholder="Masukkan catatan"
                                />
                            </a-form-item>
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
                :loading="loadingFormPerilaku"
            >
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'akidah'">
                        <a>
                            {{ record.name }}
                        </a>
                    </template>
                    <template v-else-if="column.key === 'point'">
                        <a-form-item
                            :name="[record.name.replace(/\s+/g, ''), 'nilai']"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Poin tidak boleh kosong',
                                    trigger: 'blur'
                                },
                                {
                                    pattern: new RegExp('^[0-9]*$'),
                                    message: 'Poin harus berupa angka',
                                    trigger: 'blur'
                                }
                            ]"
                        >
                            <a-input
                                v-model:value="
                                    formPerilaku[
                                        record.name.replace(/\s+/g, '')
                                    ].nilai
                                "
                                placeholder="Masukkan poin"
                            />
                        </a-form-item>
                    </template>
                    <template v-else-if="column.key === 'catatan'">
                        <a-form-item
                            :name="[record.name.replace(/\s+/g, ''), 'catatan']"
                        >
                            <a-input
                                v-model:value="
                                    formPerilaku[
                                        record.name.replace(/\s+/g, '')
                                    ].catatan
                                "
                                placeholder="Masukkan catatan"
                            />
                        </a-form-item>
                    </template>
                </template>
            </a-table>

            <a-table
                :dataSource="dataSourceAkhlak"
                :columns="columnAkhlak"
                :pagination="false"
                bordered
                style="margin-bottom: 6px"
                :loading="loadingFormPerilaku"
            >
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'akhlak'">
                        <a>
                            {{ record.name }}
                        </a>
                    </template>
                    <template v-else-if="column.key === 'point'">
                        <a-form-item
                            :name="[record.name.replace(/\s+/g, ''), 'nilai']"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Poin tidak boleh kosong',
                                    trigger: 'blur'
                                },
                                {
                                    pattern: new RegExp('^[0-9]*$'),
                                    message: 'Poin harus berupa angka',
                                    trigger: 'blur'
                                }
                            ]"
                        >
                            <a-input
                                v-model:value="
                                    formPerilaku[
                                        record.name.replace(/\s+/g, '')
                                    ].nilai
                                "
                                placeholder="Masukkan poin"
                            />
                        </a-form-item>
                    </template>
                    <template v-else-if="column.key === 'catatan'">
                        <a-form-item
                            :name="[record.name.replace(/\s+/g, ''), 'catatan']"
                        >
                            <a-input
                                v-model:value="
                                    formPerilaku[
                                        record.name.replace(/\s+/g, '')
                                    ].catatan
                                "
                                placeholder="Masukkan catatan"
                            />
                        </a-form-item>
                    </template>
                </template>
            </a-table>

            <a-table
                :dataSource="dataSourceKedisplinan"
                :columns="columnKedisplinan"
                :pagination="false"
                bordered
                style="margin-bottom: 6px"
                :loading="loadingFormPerilaku"
            >
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'kedisplinan'">
                        <a>
                            {{ record.name }}
                        </a>
                    </template>
                    <template v-else-if="column.key === 'point'">
                        <a-form-item
                            :name="[record.name.replace(/\s+/g, ''), 'nilai']"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Poin tidak boleh kosong',
                                    trigger: 'blur'
                                },
                                {
                                    pattern: new RegExp('^[0-9]*$'),
                                    message: 'Poin harus berupa angka',
                                    trigger: 'blur'
                                }
                            ]"
                        >
                            <a-input
                                v-model:value="
                                    formPerilaku[
                                        record.name.replace(/\s+/g, '')
                                    ].nilai
                                "
                                placeholder="Masukkan poin"
                            />
                        </a-form-item>
                    </template>
                    <template v-else-if="column.key === 'catatan'">
                        <a-form-item
                            :name="[record.name.replace(/\s+/g, ''), 'catatan']"
                        >
                            <a-input
                                v-model:value="
                                    formPerilaku[
                                        record.name.replace(/\s+/g, '')
                                    ].catatan
                                "
                                placeholder="Masukkan catatan"
                            />
                        </a-form-item>
                    </template>
                </template>
            </a-table>

            <a-table
                :dataSource="dataSourceKerapian"
                :columns="columnKerapian"
                :pagination="false"
                bordered
                style="margin-bottom: 6px"
                :loading="loadingFormPerilaku"
            >
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'kerapian'">
                        <a>
                            {{ record.name }}
                        </a>
                    </template>
                    <template v-else-if="column.key === 'point'">
                        <a-form-item
                            :name="[record.name.replace(/\s+/g, ''), 'nilai']"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Poin tidak boleh kosong',
                                    trigger: 'blur'
                                },
                                {
                                    pattern: new RegExp('^[0-9]*$'),
                                    message: 'Poin harus berupa angka',
                                    trigger: 'blur'
                                }
                            ]"
                        >
                            <a-input
                                v-model:value="
                                    formPerilaku[
                                        record.name.replace(/\s+/g, '')
                                    ].nilai
                                "
                                placeholder="Masukkan poin"
                            />
                        </a-form-item>
                    </template>
                    <template v-else-if="column.key === 'catatan'">
                        <a-form-item
                            :name="[record.name.replace(/\s+/g, ''), 'catatan']"
                        >
                            <a-input
                                v-model:value="
                                    formPerilaku[
                                        record.name.replace(/\s+/g, '')
                                    ].catatan
                                "
                                placeholder="Masukkan catatan"
                            />
                        </a-form-item>
                    </template>
                </template>
            </a-table>
            <a-form-item>
                <a-button html-type="submit" type="primary"> Simpan </a-button>
            </a-form-item>
        </a-form>
    </a-modal>
</template>

<script>
import { surah, kegiatan, sholat } from '../../module/laporanHarian'
import moment from 'moment'
import 'moment/locale/id'
import {
    SearchOutlined,
    DownloadOutlined,
    UploadOutlined
} from '@ant-design/icons-vue'
const columns = [
    {
        title: 'No.',
        dataIndex: 'key',
        key: 'no'
    },
    {
        title: 'NIS/NISN',
        dataIndex: 'nisn',
        key: 'nisn'
    },
    {
        title: 'Nama Siswa',
        dataIndex: 'nama',
        key: 'nama'
    },
    {
        title: 'Aksi',
        key: 'action'
    }
]

const kolom1 = [
    {
        title: 'Kegiatan',
        dataIndex: 'kegiatanibadah',
        key: 'kegiatanibadah',
        width: 300
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
        key: 'shalat',
        width: 300
    },
    {
        title: 'Aksi',
        key: 'aksi'
    }
]

const kolom3 = [
    {
        title: 'Ibadah',
        dataIndex: 'ibadah',
        key: 'ibadah'
    },
    {
        title: 'Point',
        key: 'point'
    },
    {
        title: 'Catatan',
        key: 'catatan'
    }
]
const dataSource3 = [
    {
        key: '1',
        ibadah: 'Menjadi Imam/Muadzin'
    },
    {
        key: '2',
        ibadah: 'Infaq Terbaik Jumat'
    },
    {
        key: '3',
        ibadah: 'Berbagi makanan dan perlengkapan'
    },
    {
        key: '4',
        ibadah: 'Mengutip dan membuang Sampah pada tempatnya'
    },
    {
        key: '5',
        ibadah: 'Mendamaikan teman yang berselisih'
    },
    {
        key: '6',
        ibadah: 'Menjadi Tentor Sebaya ketika Belajar'
    },
    {
        key: '7',
        ibadah: 'Merapikan Sandal dan sepatu'
    },
    {
        key: '8',
        ibadah: 'Menunaikan Tugas Piket'
    }
]
const kolom4 = [
    {
        title: 'Aqidah',
        dataIndex: 'aqidah',
        key: 'aqidah'
    },
    {
        title: 'Point',
        key: 'point'
    },
    {
        title: 'Catatan',
        key: 'catatan'
    }
]
const dataSource4 = [
    {
        key: '1',
        aqidah: 'Membawa Jimat (Benda yang dianggap bisa menolong)'
    },
    {
        key: '2',
        aqidah: 'Membawa Buku-buku Zodiak'
    },
    {
        key: '3',
        aqidah: 'Telat sholat berjamaah'
    },
    {
        key: '4',
        aqidah: 'Tidak menjaga adab dalam masjid (doa dan Dzikir)'
    },
    {
        key: '5',
        aqidah: 'Berkelahi'
    },
    {
        key: '6',
        aqidah: 'Mencuri'
    },
    {
        key: '7',
        aqidah: 'Berdusta'
    },
    {
        key: '8',
        aqidah: 'Melakukan perbuatan asusila'
    },
    {
        key: '9',
        aqidah: 'Merokok dan Narkoba'
    },
    {
        key: '10',
        aqidah: 'Berperilaku dan bertutur kata tidak sopan, kasar /Kotor'
    },
    {
        key: '11',
        aqidah: 'Memanfaatkan/ memakai barang milik orang lain tanpa izin'
    },
    {
        key: '12',
        aqidah: 'Membully Teman'
    },
    {
        key: '13',
        aqidah: 'Berpacaran'
    },
    {
        key: '14',
        aqidah: 'Makan sambil berdiri dan atau berjalan'
    },
    {
        key: '15',
        aqidah: 'Tidak Antri dalam setuap Urusan'
    },
    {
        key: '16',
        aqidah: 'Keluar sekolah/ kelas tanpa izin'
    },
    {
        key: '17',
        aqidah: 'Membawa HP'
    },
    {
        key: '18',
        aqidah: 'Membawa perhiasan berharga'
    },
    {
        key: '19',
        aqidah: 'Membawa Uang Jajan'
    },
    {
        key: '20',
        aqidah: 'Merusak peralatan sekolah'
    },
    {
        key: '21',
        aqidah: 'Membawa sepeda motor'
    },
    {
        key: '22',
        aqidah: 'Tidak melaksanakan piket kelas'
    },
    {
        key: '23',
        aqidah: 'Tidak memakai seragam sesuai jadwal'
    },
    {
        key: '24',
        aqidah: 'Berambut dan berkuku Panjang bagi laki-laki'
    },
    {
        key: '25',
        aqidah: 'Membuang Sampah tidak pada tempatnya'
    }
]
export default {
    data() {
        return {
            formTahfidz: {},
            formTahfidzSingle: {
                surah: 'Al-Fatihah',
                ayat: null,
                tanggal: null,
                user_id: null
            },
            siswaTable: [],
            showModal1: false,
            showModal2: false,
            showModal3: false,
            showModal4: false,
            showModal5: false,
            DataSourceSiswa: [],
            columns,
            dataSource1: kegiatan,
            kolom1,
            dataSource2: sholat,
            kolom2,
            dataSource3,
            kolom3,
            dataSource4,
            kolom4,
            classes: [],
            filter: {
                class: null,
                tanggal: moment().format('YYYY-MM-DD')
            },
            surah,
            dataReady: false,
            tanggal: null,
            selectedTahfidz: null,
            clickUserid: null,
            loadingFormTahfiz: false,
            formMutabaahYaumiyah: {
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
            loadingFormMutabaahYaumiyah: false,
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
            formPerilaku: {
                tanggal: moment().format('YYYY-MM-DD')
            },
            loadingFormPerilaku: false
        }
    },
    created() {
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
        getAllSiswa() {
            this.tanggal = this.filter.tanggal
            this.dataReady = true
            this.DataSourceSiswa = []
            const vm = this
            vm.loading = true
            const params = {
                req: 'table',
                kelas_id: vm.filter.class
            }
            vm.axios
                .get(vm.url('siswa/read'), { params })
                .then((response) => {
                    vm.loading = false
                    const data = response.data.models
                    data.forEach((item, index) => {
                        this.DataSourceSiswa.push({
                            id: item.user_id,
                            key: index + 1,
                            nisn: item.nisn,
                            nama: item.user.name
                        })
                    })

                    console.log(this.DataSourceSiswa)
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        onFinishFilter() {
            this.getAllSiswa()
        },
        onFinishPerilaku() {
            const tanggal = this.formPerilaku.tanggal
            let perilaku = Object.values(this.formPerilaku)
            perilaku.shift()
            perilaku = perilaku.map((item) => {
                return {
                    tanggal: tanggal,
                    ...item
                }
            })

            this.axios
                .post(this.url('laporan/perilaku/write'), perilaku)
                .then((res) => {
                    this.showModal3 = false
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

            console.log(this.formPerilaku)
        },
        loadFormTahfiz(userId) {
            this.showModal1 = true
            this.formTahfidz = []
            this.formTahfidzSingle.tanggal = this.tanggal
            this.loadingFormTahfiz = true
            this.formTahfidzSingle.user_id = userId
            this.clickUserid = userId
            this.formTahfidzSingle.ayat = null
            const vm = this
            vm.loading = true
            const params = {
                req: 'get_tahfidz_siswa',
                user_id: userId,
                tanggal: this.tanggal
            }
            vm.axios
                .get(vm.url('siswa/read'), { params })
                .then((response) => {
                    vm.loading = false
                    const data = response.data.models
                    console.log(data)
                    data[0].laporan_tahfidz.forEach((item, index) => {
                        this.formTahfidz.push({
                            id: item.id,
                            user_id: item.user_id,
                            surah: item.surah,
                            ayat: `${item.ayat_start}-${item.ayat_end}`
                        })
                    })

                    this.loadingFormTahfiz = false
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        loadFormTahfizSingle(tahfidzId) {
            this.selectedTahfidz = tahfidzId
            console.log(this.formTahfidz)
            if (tahfidzId) {
                this.formTahfidz.filter((item) => {
                    if (item.id == tahfidzId) {
                        this.formTahfidzSingle = item
                    }

                    console.log(this.formTahfidzSingle)
                })
            } else {
                this.formTahfidzSingle = {
                    surah: 'Al-Fatihah',
                    ayat: null,
                    user_id: this.clickUserid,
                    tanggal: this.tanggal
                }
            }
        },
        onFinishTahfidzSingle() {
            console.log(this.formTahfidzSingle)
            this.axios
                .post(this.url('laporan/tahfidz/write'), this.formTahfidzSingle)
                .then((res) => {
                    this.selectedTahfidz = null
                    this.loadFormTahfiz(this.clickUserid)
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil disimpan'
                    })
                })
                .catch((e) => {
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: JSON.stringify(e.response.data)
                    })
                })
        },
        loadFormMutabaahYaumiyah(userId) {
            this.showModal2 = true
            this.loadingFormMutabaahYaumiyah = true
            this.clickUserid = userId
            this.formMutabaahYaumiyah = {
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
                Tanggal: this.tanggal
            }
            const vm = this
            vm.loading = true
            const params = {
                req: 'get_mutabaahyaumiyah_by_user_id_and_date',
                user_id: userId,
                tanggal: this.tanggal
            }
            vm.axios
                .get(vm.url('laporan/mutabaah-yaumiyah/read'), { params })
                .then((response) => {
                    console.log(response)
                    vm.loading = false
                    vm.loadingFormMutabaahYaumiyah = false
                    const data = response.data.data
                    if (data) {
                        console.log(data)
                        this.formMutabaahYaumiyah = {
                            QiyamulLail: data.qiyamul_lail,
                            Dhuha: data.dhuha,
                            TilawahQuran: data.tilawah_quran,
                            MembacaBuku: data.membaca_buku,
                            Olahraga: data.olahraga,
                            AlMatsurat: data.al_matsurat,
                            ShoumSunnah: data.shoum_sunnah,
                            ShalatSubuh: data.shalat_subuh,
                            ShalatDzuhur: data.shalat_dzuhur,
                            ShalatAshar: data.shalat_ashar,
                            ShalatMaghrib: data.shalat_maghrib,
                            ShalatIsya: data.shalat_isya,
                            Tanggal: data.tanggal
                        }
                    }
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        loadFormPerilakuData(userId) {
            this.showModal3 = true
            this.clickUserid = userId
            this.loadingFormPerilaku = true
            this.axios.get(this.url('laporan/perilaku/data')).then((res) => {
                console.log(res)
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

                this.dataSourceKedisplinan = response.filter((item, index) => {
                    return item.type === 'kedisiplinan'
                })

                this.dataSourceKerapian = response.filter((item, index) => {
                    return item.type === 'kebersihandankerapian'
                })

                response.forEach((item, index) => {
                    this.formPerilaku = {
                        ...this.formPerilaku,
                        [item.name.replace(/\s+/g, '')]: {
                            perilaku_id: item.id,
                            user_id: userId,
                            nilai: null,
                            catatan: null
                        }
                    }
                })

                this.axios
                    .get(this.url('laporan/perilaku/data'), {
                        params: {
                            user_id: userId,
                            tanggal: this.tanggal,
                            req: 'get_perilaku_by_user_id_and_date'
                        }
                    })
                    .then((res) => {
                        this.loading = false
                        const response = res.data.data
                        response.forEach((item, index) => {
                            this.formPerilaku = {
                                ...this.formPerilaku,
                                [item.list_perilaku.name.replace(/\s+/g, '')]: {
                                    perilaku_id: item.list_perilaku.id,
                                    user_id: userId,
                                    nilai: item.nilai,
                                    catatan: item.catatan
                                }
                            }
                        })
                        this.loadingFormPerilaku = false
                    })
                    .catch((e) => {
                        console.log(e)
                    })
            })
        },
        deleteTahfidz(id) {
            console.log(id)
            this.axios
                .delete(this.url('laporan/tahfidz/delete/' + id))
                .then((res) => {
                    console.log(res)
                    this.selectedTahfidz = null
                    this.loadFormTahfiz(this.clickUserid)
                    this.formTahfidzSingle = {
                        surah: 'Al-Fatihah',
                        ayat: null,
                        user_id: this.clickUserid,
                        tanggal: this.tanggal
                    }
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil dihapus'
                    })
                })
                .catch((e) => {
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: JSON.stringify(e.response.data)
                    })
                })
        },
        saveMutabaahYaumiah() {
            this.axios
                .post(this.url('laporan/mutabaah-yaumiyah/write'), {
                    req: 'write_mutabaahyaumiyah_by_user_id_and_date',
                    user_id: this.clickUserid,
                    tanggal: this.tanggal,
                    ...this.formMutabaahYaumiyah
                })
                .then((res) => {
                    console.log(res)
                    this.$notification.success({
                        message: 'Berhasil',
                        description: 'Data berhasil disimpan'
                    })
                    this.showModal2 = false
                })
                .catch((e) => {
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: JSON.stringify(e.response.data)
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
