<template>
    <h1 style="margin-left: 30px">Laporan Harian Guru</h1>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="Pilih Kriteria"
                style="width: 100%; margin-bottom: 20px"
            >
                <a-space
                    style="
                        display: flex;
                        justify-content: flex-container;
                        flex-wrap: wrap;
                        margin-bottom: 20px;
                    "
                >
                    <a-select
                        v-model:value="filter.class"
                        style="width: 345px"
                        placeholder="Kelas"
                        @change="(e) => (filter.class = e.value)"
                    >
                        <a-select-option value="VII">VII</a-select-option>
                        <a-select-option value="VIII">VIII</a-select-option>
                        <a-select-option value="IX">IX</a-select-option>
                    </a-select>
                    <a-date-picker
                        v-model:value="filter.tanggal"
                        style="width: 345px"
                        placeholder="Tanggal Dari"
                    />
                    <a-date-picker
                        v-model:value="filter.tanggal"
                        style="width: 345px"
                        placeholder="Tanggal Sampai"
                    />
                </a-space>
                <a-space
                    direction="horizontal"
                    style="display: flex; justify-content: flex-end"
                >
                    <a-button
                        type="primary"
                        style="display: inline-flex; align-items: center"
                    >
                        <template #icon><SearchOutlined /></template>
                        Cari
                    </a-button>
                </a-space>
            </a-card>
            <a-card :loading="loading" title="Daftar Guru" style="width: 100%">
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
                        placeholder="Cari Guru"
                        style="width: 200px"
                        @search="readData"
                    />
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
                        <template v-else-if="column.key === 'action'">
                            <a-button 
                            type="primary"
                            @click="showModal1 = true"
                            style="margin-bottom: 4px; margin-right: 6px">Tahfidz</a-button>
                            <a-button 
                            type="primary"
                            @click="showModal2 = true"
                            style="margin-bottom: 4px; margin-right: 6px">Ibadah Harian</a-button>
                            <a-button 
                            type="primary"
                            @click="showModal3 = true">Perilaku Harian</a-button>
                        </template>
                    </template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>
    <!-- modal bagian tahfidz -->
    <a-modal
        v-model:visible="showModal1"
        title="Posting Laporan Tahfidz"
        @ok="showModal1 = false"
    >
        <a-select
            v-model:value="filter.surah"
            style="width: 345px"
            placeholder="Nama Surah"
            @change="(e) => (filter.surah = e.value)"
        >
            <a-select-option value="1">Al-Fatihah</a-select-option>
            <a-select-option value="2">Al-Baqarah</a-select-option>
            <a-select-option value="3">Ali Imran</a-select-option>
            <a-select-option value="4">An-Nisa</a-select-option>
            <a-select-option value="5">Al-Ma'idah</a-select-option>
            <a-select-option value="6">Al-An'am</a-select-option>
            <a-select-option value="7">Al-A'raf</a-select-option>
            <a-select-option value="8">Al-Anfal</a-select-option>
            <a-select-option value="9">At-Taubah</a-select-option>
            <a-select-option value="10">Yunus</a-select-option>
            <a-select-option value="11">Hud</a-select-option>
            <a-select-option value="12">Yusuf</a-select-option>
            <a-select-option value="13">Ar-Ra'd</a-select-option>
            <a-select-option value="14">Ibrahim</a-select-option>
            <a-select-option value="15">Al-Hijr</a-select-option>
            <a-select-option value="16">An-Nahl</a-select-option>
            <a-select-option value="17">Al-Isra</a-select-option>
            <a-select-option value="18">Al-Kahf</a-select-option>
            <a-select-option value="19">Maryam</a-select-option>
            <a-select-option value="20">Taha</a-select-option>
            <a-select-option value="21">Al-Anbiya</a-select-option>
            <a-select-option value="22">Al-Hajj</a-select-option>
            <a-select-option value="23">Al-Mu'minun</a-select-option>
            <a-select-option value="24">An-Nur</a-select-option>
            <a-select-option value="25">Al-Furqan</a-select-option>
            <a-select-option value="26">Asy-Syu'ara</a-select-option>
            <a-select-option value="27">An-Naml</a-select-option>
            <a-select-option value="28">Al-Qasas</a-select-option>
            <a-select-option value="29">Al-Ankabut</a-select-option>
            <a-select-option value="30">Ar-Rum</a-select-option>
            <a-select-option value="31">Luqman</a-select-option>
            <a-select-option value="32">As-Sajdah</a-select-option>
            <a-select-option value="33">Al-Ahzab</a-select-option>
            <a-select-option value="34">Saba'</a-select-option>
            <a-select-option value="35">Fatir</a-select-option>
            <a-select-option value="36">Yasin</a-select-option>
            <a-select-option value="37">As-Saffat</a-select-option>
            <a-select-option value="38">Sad</a-select-option>
            <a-select-option value="39">Az-Zumar</a-select-option>
            <a-select-option value="40">Gafir</a-select-option>
            <a-select-option value="41">Fussilat</a-select-option>
            <a-select-option value="42">Asy-Syura</a-select-option>
            <a-select-option value="43">Az-Zukhruf</a-select-option>
            <a-select-option value="44">Ad-Dukhan</a-select-option>
            <a-select-option value="45">Al-Jasiyah</a-select-option>
            <a-select-option value="46">Al-Ahqaf</a-select-option>
            <a-select-option value="47">Muhammad</a-select-option>
            <a-select-option value="48">Al-Fath</a-select-option>
            <a-select-option value="49">Al-Hujarat</a-select-option>
            <a-select-option value="50">Qaf</a-select-option>
            <a-select-option value="51">Az-Zariyat</a-select-option>
            <a-select-option value="52">At-Tur</a-select-option>
            <a-select-option value="53">An-Najm</a-select-option>
            <a-select-option value="54">Al-Qamar</a-select-option>
            <a-select-option value="55">Ar-Rahman</a-select-option>
            <a-select-option value="56">Al-Waqi'ah</a-select-option>
            <a-select-option value="57">Al-Hadid</a-select-option>
            <a-select-option value="58">Al-Mujadalah</a-select-option>
            <a-select-option value="59">Al-Hasyr</a-select-option>
            <a-select-option value="60">Al-Mumtahanah</a-select-option>
            <a-select-option value="61">Ash-Shaff</a-select-option>
            <a-select-option value="62">Al-Jumu'ah</a-select-option>
            <a-select-option value="63">Al-Munafiqun</a-select-option>
            <a-select-option value="64">At-Taghabun</a-select-option>
            <a-select-option value="65">Ath-Thalaq </a-select-option>
            <a-select-option value="66">At-Tahrim</a-select-option>
            <a-select-option value="67">Al-Mulk</a-select-option>
            <a-select-option value="68">Al-Qalam</a-select-option>
            <a-select-option value="69">Al-Haqqah</a-select-option>
            <a-select-option value="70">Al-Ma'arij</a-select-option>
            <a-select-option value="71">Nuh</a-select-option>
            <a-select-option value="72">Al-Jin</a-select-option>
            <a-select-option value="73">Al-Muzammil</a-select-option>
            <a-select-option value="74">Al-Muddatsir</a-select-option>
            <a-select-option value="75">Al-Qiyamah</a-select-option>
            <a-select-option value="76">Al-Insan</a-select-option>
            <a-select-option value="77">Al-Mursalat</a-select-option>
            <a-select-option value="78">An-Naba</a-select-option>
            <a-select-option value="79">An-Nazi'at</a-select-option>
            <a-select-option value="80">'Abasa</a-select-option>
            <a-select-option value="81">At-Takwir</a-select-option>
            <a-select-option value="82">Al-Infithar</a-select-option>
            <a-select-option value="83">Al-Muthaffifin</a-select-option>
            <a-select-option value="84">Al-Insyiqaq </a-select-option>
            <a-select-option value="85">Al-Buruj</a-select-option>
            <a-select-option value="86">Ath-Thariq</a-select-option>
            <a-select-option value="87">Al-A'la</a-select-option>
            <a-select-option value="88">Al-Ghasyiyah</a-select-option>
            <a-select-option value="89">Al-Fajr</a-select-option>
            <a-select-option value="90">Al-Balad</a-select-option>
            <a-select-option value="91">Asy-Syams</a-select-option>
            <a-select-option value="92">Al-Lail</a-select-option>
            <a-select-option value="93">Adh-Dhuha</a-select-option>
            <a-select-option value="94">Al-Insyirah</a-select-option>
            <a-select-option value="95">At-Tin</a-select-option>
            <a-select-option value="96">Al-'Alaq</a-select-option>
            <a-select-option value="97">Al-Qadr</a-select-option>
            <a-select-option value="98">Al-Bayyinah</a-select-option>
            <a-select-option value="99">Az-Zalzalah</a-select-option>
            <a-select-option value="100">Al-'Adiyat</a-select-option>
            <a-select-option value="101">Al-Qari'ah</a-select-option>
            <a-select-option value="102">At-Takatsur</a-select-option>
            <a-select-option value="103">Al-'Ashr</a-select-option>
            <a-select-option value="104">Al-Humazah</a-select-option>
            <a-select-option value="105">Al-Fil</a-select-option>
            <a-select-option value="106">Quraisy</a-select-option>
            <a-select-option value="107">Al-Ma'un</a-select-option>
            <a-select-option value="108">Al-Kautsar</a-select-option>
            <a-select-option value="109">Al-Kafirun</a-select-option>
            <a-select-option value="110">An-Nashr</a-select-option>
            <a-select-option value="111">Al-Masad</a-select-option>
            <a-select-option value="112">Al-Ikhlash</a-select-option>
            <a-select-option value="113">Al-Falaq</a-select-option>
            <a-select-option value="114">An-Nas</a-select-option>
        </a-select>
        <p>Nama Surah</p>
        <a-date-picker
            v-model:value="filter.tanggal"
            style="width: 345px"
            placeholder="Tanggal Posting"
        />
        <p>Tanggal Posting</p>
        <a-input-search
            v-model:value="filter.search"
            placeholder="Contoh : Ayat 1-5"
            style="width: 200px"
            @search="readData"
        />
        <p>Keterangan Ayat Surah</p>
        <a-upload
            v-model:file-list="fileList"
            name="file"
            action="https://www.mocky.io/v2/5cc8019d300000980a055e76"
            :headers="headers"
            @change="handleChange"
        >
            <a-button>
                <upload-outlined></upload-outlined>
                Click to Upload
            </a-button>
        </a-upload>
        <p>Lampiran / Foto</p>
    </a-modal>
    <!-- modal bagian ibadah harian -->
    <a-modal
        v-model:visible="showModal2"
        title="Posting Mutaba'ah Ibadah"
        @ok="showModal2 = false"
    >
        <a-date-picker
            v-model:value="filter.tanggal"
            style="width: 345px"
            placeholder="Tanggal Posting"
        />
        <a-table
            :dataSource="dataSource1"
            :columns="kolom1"
            :scroll="{ y: 300 }"
            :pagination="false"
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'kegiatanibadah'">
                    <a>
                        {{ record.kegiatanibadah }}
                    </a>
                </template>
                <template v-else-if="column.key === 'aksi'">
                    <a-checkbox :value="1"></a-checkbox>
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
                    <a-checkbox :value="1"></a-checkbox>
                </template>
            </template>
        </a-table>
    </a-modal>
    <!-- modal bagian perilaku harian -->
    <a-modal
        v-model:visible="showModal3"
        title="Posting Laporan Perilaku Harian Guru"
        @ok="showModal3 = false"
        width="1000px"
    >
        <a-date-picker
            v-model:value="filter.tanggal"
            style="width: 345px; margin-bottom: 6px"
            placeholder="Tanggal Posting"
        />
        <!-- perilaku terpuji  -->
        <a-table
            :dataSource="dataSource3"
            :columns="kolom3"
            :scroll="{ y: 300 }"
            :pagination="false"
            style="margin-bottom: 6px"
            bordered
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'ibadah'">
                    <a>
                        {{ record.ibadah }}
                    </a>
                </template>
                <template v-else-if="column.key === 'point'">
                    <a-input
                        v-model:value="value"
                        placeholder="Input Nilai Point"
                    />
                </template>
                <template v-else-if="column.key === 'catatan'">
                    <a-input v-model:value="value" placeholder="Catatan" />
                </template>
            </template>
        </a-table>

        <!-- perilaku tidak terpuji  -->
        <a-table
            :dataSource="dataSource4"
            :columns="kolom4"
            :scroll="{ y: 300 }"
            :pagination="false"
            bordered
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'aqidah'">
                    <a>
                        {{ record.aqidah }}
                    </a>
                </template>
                <template v-else-if="column.key === 'point'">
                    <a-input :value="isi" placeholder="Input Nilai Point" />
                </template>
                <template v-else-if="column.key === 'catatan'">
                    <a-input :value="isi" placeholder="Catatan" />
                </template>
            </template>
        </a-table>
    </a-modal>
</template>

<script>
import { message } from 'ant-design-vue'
import {
    SearchOutlined,
    DownloadOutlined,
    UploadOutlined
} from '@ant-design/icons-vue'
import { defineComponent, ref } from 'vue'
const columns = [
    {
        title: 'No.',
        dataIndex: 'key',
        key: 'no'
    },
    {
        title: 'NIP',
        dataIndex: 'nip',
        key: 'nip'
    },
    {
        title: 'Nama Guru',
        dataIndex: 'nama',
        key: 'nama'
    },
    {
        title: 'Aksi',
        key: 'action'
    }
]
const data = [
    {
        key: '1',
        nip: 2910398212,
        nama: 'Muhammad Farhan Syahreza'
    },
    {
        key: '2',
        nip: 2910398212,
        nama: 'Muhammad Farhan Syahreza'
    },
    {
        key: '3',
        nip: 2910398212,
        nama: 'Muhammad Farhan Syahreza'
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
        kegiatanibadah: 'Al-Matsurat'
    },
    {
        key: '7',
        kegiatanibadah: 'Shoum Sunnah'
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
export default defineComponent({
    setup() {
        const value = ref('')
        const showModal1 = ref(false)
        const showModal2 = ref(false)
        const showModal3 = ref(false)
        const handleChange = (info) => {
            if (info.file.status !== 'uploading') {
                console.log(info.file, info.fileList)
            }
            if (info.file.status === 'done') {
                message.success(`${info.file.name} file uploaded successfully`)
            } else if (info.file.status === 'error') {
                message.error(`${info.file.name} file upload failed.`)
            }
        }
        const fileList = ref([])
        return {
            showModal1,
            showModal2,
            showModal3,
            value,
            fileList,
            headers: {
                authorization: 'authorization-text'
            },
            handleChange
        }
    },
    components: {
        SearchOutlined,
        DownloadOutlined,
        UploadOutlined
    },
    data() {
        return {
            data,
            columns,
            dataSource1,
            kolom1,
            dataSource2,
            kolom2,
            dataSource3,
            kolom3,
            dataSource4,
            kolom4,
            classes: [],
            filter: {
                class: null,
                tanggal: null
            }
        }
    }
})
</script>
