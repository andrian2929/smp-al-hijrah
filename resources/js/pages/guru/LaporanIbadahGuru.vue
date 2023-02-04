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
                                        v-model:value="formTahfidz.tanggal"
                                        style="width: 200px"
                                        placeholder="Tanggal Posting"
                                        value-format="YYYY-MM-DD"
                                    />
                                </a-form-item>
                                <a-form-item>
                                    <a-select
                                        v-model:value="formTahfidz.surah"
                                        placeholder="Nama Surah"
                                        @change="onChangeSurah"
                                    >
                                        <a-select-option
                                            v-for="surah in surahData"
                                            :key="surah.key"
                                            :value="surah.surah"
                                        >
                                            {{ surah.surah }}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                                <a-form-item
                                    name="ayat"
                                    :rules="[
                                        {
                                            required: true,
                                            message: 'Ayat harus diisi'
                                        },
                                        {
                                            pattern: /^[0-9]+-[0-9]+$/,
                                            message: 'Format ayat salah'
                                        }
                                    ]"
                                >
                                    <a-input
                                        placeholder="Contoh : 1-5"
                                        style="width: 200px"
                                        v-model:value="formTahfidz.ayat"
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
                                        <template #icon
                                            ><save-outlined /></template
                                        >Simpan
                                    </a-button>
                                </a-form-item>
                            </a-space>
                        </a-form>
                    </a-card>
                    <a-card
                        :loading="loading"
                        title="Laporan Tahfidz"
                        style="width: 100%"
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
                        </a-card>
                        <a-card
                            :loading="loading"
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
                            >
                                <template #bodyCell="{ column, record }">
                                    <div v-if="record.type === 'ibadah'">
                                        <template
                                            v-if="column.key === 'ibadah'"
                                        >
                                            <a>
                                                {{ record.name }}
                                            </a>
                                        </template>
                                        <template
                                            v-else-if="column.key === 'point'"
                                        >
                                            <a-form-item
                                                :name="[
                                                    record.name.replace(
                                                        /\s+/g,
                                                        ''
                                                    ),
                                                    'nilai'
                                                ]"
                                                :rules="[
                                                    {
                                                        required: true,
                                                        message:
                                                            'Poin tidak boleh kosong'
                                                    },
                                                    {
                                                        pattern: new RegExp(
                                                            '^[0-9]*$'
                                                        ),
                                                        message:
                                                            'Poin harus berupa angka'
                                                    }
                                                ]"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formPerilaku[
                                                            record.name.replace(
                                                                /\s+/g,
                                                                ''
                                                            )
                                                        ].nilai
                                                    "
                                                    placeholder="Masukkan poin"
                                                />
                                            </a-form-item>
                                        </template>
                                        <template
                                            v-else-if="column.key === 'catatan'"
                                        >
                                            <a-form-item
                                                :name="[
                                                    record.name.replace(
                                                        /\s+/g,
                                                        ''
                                                    ),
                                                    'catatan'
                                                ]"
                                            >
                                                <a-input
                                                    v-model:value="
                                                        formPerilaku[
                                                            record.name.replace(
                                                                /\s+/g,
                                                                ''
                                                            )
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
                            >
                                <template #bodyCell="{ column, record }">
                                    <template v-if="column.key === 'akidah'">
                                        <a>
                                            {{ record.name }}
                                        </a>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'point'"
                                    >
                                        <a-form-item
                                            :name="[
                                                record.name.replace(/\s+/g, ''),
                                                'nilai'
                                            ]"
                                            :rules="[
                                                {
                                                    required: true,
                                                    message:
                                                        'Poin tidak boleh kosong'
                                                },
                                                {
                                                    pattern: new RegExp(
                                                        '^[0-9]*$'
                                                    ),
                                                    message:
                                                        'Poin harus berupa angka'
                                                }
                                            ]"
                                        >
                                            <a-input
                                                v-model:value="
                                                    formPerilaku[
                                                        record.name.replace(
                                                            /\s+/g,
                                                            ''
                                                        )
                                                    ].nilai
                                                "
                                                placeholder="Masukkan poin"
                                            />
                                        </a-form-item>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'catatan'"
                                    >
                                        <a-form-item
                                            :name="[
                                                record.name.replace(/\s+/g, ''),
                                                'catatan'
                                            ]"
                                        >
                                            <a-input
                                                v-model:value="
                                                    formPerilaku[
                                                        record.name.replace(
                                                            /\s+/g,
                                                            ''
                                                        )
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
                            >
                                <template #bodyCell="{ column, record }">
                                    <template v-if="column.key === 'akhlak'">
                                        <a>
                                            {{ record.name }}
                                        </a>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'point'"
                                    >
                                        <a-form-item
                                            :name="[
                                                record.name.replace(/\s+/g, ''),
                                                'nilai'
                                            ]"
                                            :rules="[
                                                {
                                                    required: true,
                                                    message:
                                                        'Poin tidak boleh kosong'
                                                },
                                                {
                                                    pattern: new RegExp(
                                                        '^[0-9]*$'
                                                    ),
                                                    message:
                                                        'Poin harus berupa angka'
                                                }
                                            ]"
                                        >
                                            <a-input
                                                v-model:value="
                                                    formPerilaku[
                                                        record.name.replace(
                                                            /\s+/g,
                                                            ''
                                                        )
                                                    ].nilai
                                                "
                                                placeholder="Masukkan poin"
                                            />
                                        </a-form-item>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'catatan'"
                                    >
                                        <a-form-item
                                            :name="[
                                                record.name.replace(/\s+/g, ''),
                                                'catatan'
                                            ]"
                                        >
                                            <a-input
                                                v-model:value="
                                                    formPerilaku[
                                                        record.name.replace(
                                                            /\s+/g,
                                                            ''
                                                        )
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
                            >
                                <template #bodyCell="{ column, record }">
                                    <template
                                        v-if="column.key === 'kedisplinan'"
                                    >
                                        <a>
                                            {{ record.name }}
                                        </a>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'point'"
                                    >
                                        <a-form-item
                                            :name="[
                                                record.name.replace(/\s+/g, ''),
                                                'nilai'
                                            ]"
                                            :rules="[
                                                {
                                                    required: true,
                                                    message:
                                                        'Poin tidak boleh kosong'
                                                },
                                                {
                                                    pattern: new RegExp(
                                                        '^[0-9]*$'
                                                    ),
                                                    message:
                                                        'Poin harus berupa angka'
                                                }
                                            ]"
                                        >
                                            <a-input
                                                v-model:value="
                                                    formPerilaku[
                                                        record.name.replace(
                                                            /\s+/g,
                                                            ''
                                                        )
                                                    ].nilai
                                                "
                                                placeholder="Masukkan poin"
                                            />
                                        </a-form-item>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'catatan'"
                                    >
                                        <a-form-item
                                            :name="[
                                                record.name.replace(/\s+/g, ''),
                                                'catatan'
                                            ]"
                                        >
                                            <a-input
                                                v-model:value="
                                                    formPerilaku[
                                                        record.name.replace(
                                                            /\s+/g,
                                                            ''
                                                        )
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
                            >
                                <template #bodyCell="{ column, record }">
                                    <template v-if="column.key === 'kerapian'">
                                        <a>
                                            {{ record.name }}
                                        </a>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'point'"
                                    >
                                        <a-form-item
                                            :name="[
                                                record.name.replace(/\s+/g, ''),
                                                'nilai'
                                            ]"
                                            :rules="[
                                                {
                                                    required: true,
                                                    message:
                                                        'Poin tidak boleh kosong'
                                                },
                                                {
                                                    pattern: new RegExp(
                                                        '^[0-9]*$'
                                                    ),
                                                    message:
                                                        'Poin harus berupa angka'
                                                }
                                            ]"
                                        >
                                            <a-input
                                                v-model:value="
                                                    formPerilaku[
                                                        record.name.replace(
                                                            /\s+/g,
                                                            ''
                                                        )
                                                    ].nilai
                                                "
                                                placeholder="Masukkan poin"
                                            />
                                        </a-form-item>
                                    </template>
                                    <template
                                        v-else-if="column.key === 'catatan'"
                                    >
                                        <a-form-item
                                            :name="[
                                                record.name.replace(/\s+/g, ''),
                                                'catatan'
                                            ]"
                                        >
                                            <a-input
                                                v-model:value="
                                                    formPerilaku[
                                                        record.name.replace(
                                                            /\s+/g,
                                                            ''
                                                        )
                                                    ].catatan
                                                "
                                                placeholder="Masukkan catatan"
                                            />
                                        </a-form-item>
                                    </template>
                                </template>
                            </a-table>

                            <a-form-item>
                                <a-button
                                    type="primary"
                                    html-type="submit"
                                    style="
                                        display: inline-flex;
                                        align-items: center;
                                        margin-top: 10px;
                                    "
                                >
                                    <template #icon><save-outlined /></template
                                    >Simpan
                                </a-button>
                            </a-form-item>
                        </a-card>
                    </a-form>
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
            dataSource1,
            kolom1,
            dataSource2,
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
                tanggal: moment().format('YYYY-MM-DD'),
                surah: 'Al-Fatihah',
                ayat: null
            },
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
                tanggal: moment().format('YYYY-MM-DD')
            },
            surahData: [
                {
                    key: '1',
                    surah: 'Al-Fatihah'
                },
                {
                    key: '2',
                    surah: 'Al-Baqarah'
                },
                {
                    key: '3',
                    surah: 'Ali Imran'
                },
                {
                    key: '4',
                    surah: 'An-Nisa'
                },
                {
                    key: '5',
                    surah: 'Al-Maidah'
                },
                {
                    key: '6',
                    surah: 'Al-Anam'
                },
                {
                    key: '7',
                    surah: 'Al-Araf'
                },
                {
                    key: '8',
                    surah: 'Al-Anfal'
                },
                {
                    key: '9',
                    surah: 'At-Taubah'
                },
                {
                    key: '10',
                    surah: 'Yunus'
                },
                {
                    key: '11',
                    surah: 'Hud'
                },
                {
                    key: '12',
                    surah: 'Yusuf'
                },
                {
                    key: '13',
                    surah: "Ar-Ra'd"
                },
                {
                    key: '14',
                    surah: 'Ibrahim'
                },
                {
                    key: '15',
                    surah: 'Al-Hijr'
                },
                {
                    key: '16',
                    surah: 'An-Nahl'
                },
                {
                    key: '17',
                    surah: 'Al-Isra'
                },
                {
                    key: '18',
                    surah: 'Al-Kahfi'
                },
                {
                    key: '19',
                    surah: 'Maryam'
                },
                {
                    key: '20',
                    surah: 'Ta-Ha'
                },
                {
                    key: '21',
                    surah: 'Al-Anbiya'
                },
                {
                    key: '22',
                    surah: 'Al-Hajj'
                },
                {
                    key: '23',
                    surah: "Al-Mu'minun"
                },
                {
                    key: '24',
                    surah: 'An-Nur'
                },
                {
                    key: '25',
                    surah: 'Al-Furqan'
                },
                {
                    key: '26',
                    surah: "Ash-Shu'ara"
                },
                {
                    key: '27',
                    surah: 'An-Naml'
                },
                {
                    key: '28',
                    surah: 'Al-Qasas'
                },
                {
                    key: '29',
                    surah: 'Al-Ankabut'
                },
                {
                    key: '30',
                    surah: 'Ar-Rum'
                },
                {
                    key: '31',
                    surah: 'Luqman'
                },
                {
                    key: '32',
                    surah: 'As-Sajdah'
                },
                {
                    key: '33',
                    surah: 'Al-Ahzab'
                },
                {
                    key: '34',
                    surah: 'Saba'
                },
                {
                    key: '35',
                    surah: 'Fatir'
                },
                {
                    key: '36',
                    surah: 'Ya-Sin'
                },
                {
                    key: '37',
                    surah: 'As-Saffat'
                },
                {
                    key: '38',
                    surah: 'Sad'
                },
                {
                    key: '39',
                    surah: 'Az-Zumar'
                },
                {
                    key: '40',
                    surah: 'Ghafir'
                },
                {
                    key: '41',
                    surah: 'Fussilat'
                },
                {
                    key: '42',
                    surah: 'Ash-Shura'
                },
                {
                    key: '43',
                    surah: 'Az-Zukhruf'
                },
                {
                    key: '44',
                    surah: 'Ad-Dukhan'
                },
                {
                    key: '45',
                    surah: 'Al-Jathiya'
                },
                {
                    key: '46',
                    surah: 'Al-Ahqaf'
                },
                {
                    key: '47',
                    surah: 'Muhammad'
                },
                {
                    key: '48',
                    surah: 'Al-Fath'
                },
                {
                    key: '49',
                    surah: 'Al-Hujurat'
                },
                {
                    key: '50',
                    surah: 'Qaf'
                },
                {
                    key: '51',
                    surah: 'Adh-Dhariyat'
                },
                {
                    key: '52',
                    surah: 'At-Tur'
                },
                {
                    key: '53',
                    surah: 'An-Najm'
                },
                {
                    key: '54',
                    surah: 'Al-Qamar'
                },
                {
                    key: '55',
                    surah: 'Ar-Rahman'
                },
                {
                    key: '56',
                    surah: "Al-Waqi'ah"
                },
                {
                    key: '57',
                    surah: 'Al-Hadid'
                },
                {
                    key: '58',
                    surah: 'Al-Mujadilah'
                },
                {
                    key: '59',
                    surah: 'Al-Hasyr'
                },
                {
                    key: '60',
                    surah: 'Al-Mumtahanah'
                },
                {
                    key: '61',
                    surah: 'As-Saff'
                },
                {
                    key: '62',
                    surah: "Al-Jumu'ah"
                },
                {
                    key: '63',
                    surah: 'Al-Munafiqun'
                },
                {
                    key: '64',
                    surah: 'At-Taghabun'
                },
                {
                    key: '65',
                    surah: 'At-Talaq'
                },
                {
                    key: '66',
                    surah: 'At-Tahrim'
                },
                {
                    key: '67',
                    surah: 'Al-Mulk'
                },
                {
                    key: '68',
                    surah: 'Al-Qalam'
                },
                {
                    key: '69',
                    surah: 'Al-Haqqah'
                },
                {
                    key: '70',
                    surah: "Al-Ma'arij"
                },
                {
                    key: '71',
                    surah: 'Nuh'
                },
                {
                    key: '72',
                    surah: 'Al-Jinn'
                },
                {
                    key: '73',
                    surah: 'Al-Muzzammil'
                },
                {
                    key: '74',
                    surah: 'Al-Muddaththir'
                },
                {
                    key: '75',
                    surah: 'Al-Qiyamah'
                },
                {
                    key: '76',
                    surah: 'Al-Insan'
                },
                {
                    key: '77',
                    surah: 'Al-Mursalat'
                },
                {
                    key: '78',
                    surah: 'An-Naba'
                },
                {
                    key: '79',
                    surah: "An-Nazi'at"
                },
                {
                    key: '80',
                    surah: "'Abasa"
                },
                {
                    key: '81',
                    surah: 'At-Takwir'
                },
                {
                    key: '82',
                    surah: 'Al-Infitar'
                },
                {
                    key: '83',
                    surah: 'Al-Mutaffifin'
                },
                {
                    key: '84',
                    surah: 'Al-Insyiqaq'
                },
                {
                    key: '85',
                    surah: 'Al-Buruj'
                },
                {
                    key: '86',
                    surah: 'At-Tariq'
                },
                {
                    key: '87',
                    surah: "Al-A'la"
                },
                {
                    key: '88',
                    surah: 'Al-Ghashiyah'
                },
                {
                    key: '89',
                    surah: 'Al-Fajr'
                },
                {
                    key: '90',
                    surah: 'Al-Balad'
                },
                {
                    key: '91',
                    surah: 'Ash-Shams'
                },
                {
                    key: '92',
                    surah: 'Al-Lail'
                },
                {
                    key: '93',
                    surah: 'Ad-Duha'
                },
                {
                    key: '94',
                    surah: 'Al-Insyirah'
                },
                {
                    key: '95',
                    surah: 'At-Tin'
                },
                {
                    key: '96',
                    surah: 'Al-Alaq'
                },
                {
                    key: '97',
                    surah: 'Al-Qadr'
                },
                {
                    key: '98',
                    surah: 'Al-Bayyinah'
                },
                {
                    key: '99',
                    surah: 'Az-Zalzalah'
                },
                {
                    key: '100',
                    surah: "Al-'Adiyat"
                },
                {
                    key: '101',
                    surah: "Al-Qari'ah"
                },
                {
                    key: '102',
                    surah: 'At-Takasur'
                },
                {
                    key: '103',
                    surah: "Al-'Asr"
                },
                {
                    key: '104',
                    surah: 'Al-Humazah'
                },
                {
                    key: '105',
                    surah: 'Al-Fil'
                },
                {
                    key: '106',
                    surah: 'Quraisy'
                },
                {
                    key: '107',
                    surah: "Al-Ma'un"
                },
                {
                    key: '108',
                    surah: 'Al-Kausar'
                },
                {
                    key: '109',
                    surah: 'Al-Kafirun'
                },
                {
                    key: '110',
                    surah: 'An-Nasr'
                },
                {
                    key: '111',
                    surah: 'Al-Lahab'
                },
                {
                    key: '112',
                    surah: 'Al-Ikhlas'
                },
                {
                    key: '113',
                    surah: 'Al-Falaq'
                },
                {
                    key: '114',
                    surah: 'An-Nas'
                }
            ]
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
            this.saveLaporanTahfidz()
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
            console.log(perilaku)
            this.axios
                .post(this.url('laporan/perilaku/write'), perilaku)
                .then((res) => {
                    this.readData()
                    window.scrollTo(0, 0)
                    this.$notification.success({
                        message: 'Berhasil',
                        description: res.data.message
                    })
                })
                .catch((e) => {
                    console.log(e)
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: JSON.stringify(e.response.data.errors)
                    })
                })
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
