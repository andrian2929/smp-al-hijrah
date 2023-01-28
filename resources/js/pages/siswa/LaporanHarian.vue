<template>
<h1 style="margin-left: 30px">Laporan Harian Siswa</h1>
  <a-row type="flex" justify="center">
    <a-col :xs="23">
      <a-tabs v-model:activeKey="activeKey">
        <a-tab-pane key="1" tab="Tahfidz">
        <a-card :loading="loading" title="" style="width: 100%; margin-bottom: 20px">
            <a-form
                :model="formTahfidz"
                @finish="onFinish"
                @finishFailed="onFinishFailed"
                ref="formTahfidz"
            >
              <a-space
              style="display: flex; justify-content: flex-container; flex-wrap: wrap;"
              >
              <a-form-item
                name="tanggal"
                :rules="[
                          {
                              required: true,
                              message: 'Tanggal tidak boleh kosong'
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
            style="display: inline-flex; align-items: center"
          >
            <template #icon><save-outlined /></template>Simpan
          </a-button>
           </a-form-item>
            </a-space>
            </a-form>
      </a-card>
      <a-card :loading="loading" title="Laporan Tahfidz" style="width: 100%">
        <a-space
          direction="horizontal"
          style="display: flex; justify-content: flex-end; margin-bottom: 10px;"
          >
        </a-space>
       
        <a-table :columns="columns" :data-source="tahfizSource()" style="margin: 15px">
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
      
<!-- tab Ibadah Harian -->
          <a-tab-pane key="2" tab="Ibadah Harian" force-render>
            <a-card :loading="loading" title="Tanggal Kegiatan" style="width: 100%; margin-bottom: 20px">
                  <a-date-picker
             
                style="width: 345px"
                placeholder="Tanggal Posting"
                value-format="YYYY-MM-DD"
                v-model:value="formIbadah.Tanggal"
        
              />
            </a-card>
            <a-card :loading="loading" title="Laporan Mutaba'ah Yaumiah" style="width: 100%">
        <a-space
          direction="horizontal"
          style="display: flex; justify-content: flex-end; margin-bottom: 20px;"
          >
        </a-space>
        <a-table :dataSource="dataSource1" :columns="kolom1" :pagination="false">
        <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'kegiatanibadah'">
                    <a>
                        {{ record.kegiatanibadah }}
                    </a>
                </template>
                <template v-else-if="column.key === 'aksi'">
                    <a-checkbox v-model:checked="formIbadah[`${record.kegiatanibadah.replace(' ', '')}`]"></a-checkbox>
                </template>
            </template>
      </a-table>
      <a-table :dataSource="dataSource2" :columns="kolom2" :pagination="false">
        <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'shalat'">
                    <a>
                        {{ record.shalat }}
                    </a>
                </template>
                <template v-else-if="column.key === 'aksi'">
                    <a-checkbox v-model:checked="formIbadah[`${record.shalat.replace(' ', '')}`]"></a-checkbox>
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

<!-- tab perilaku harian -->
          <a-tab-pane key="3" tab="Perilaku Harian">
            <a-card :loading="loading" title="Tanggal Kegiatan" style="width: 100%; margin-bottom: 20px">
                  <a-date-picker
                v-model:value="filter.tanggal"
                style="width: 345px"
                placeholder="Tanggal Posting"
              />
            </a-card>
            <a-card :loading="loading" title="Laporan Perilaku" style="width: 100%">
        <a-space
          direction="horizontal"
          style="display: flex; justify-content: flex-end; margin-bottom: 20px;"
          >
        </a-space>
        <!-- perilaku terpuji  -->
      <a-table :dataSource="dataSource3" :columns="kolom3" :pagination="false" style="margin-bottom: 6px" bordered>
        <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'ibadah'">
                    <a>
                        {{ record.ibadah }}
                    </a>
                </template>
                <template v-else-if="column.key === 'point'">
                    <a-input v-model:value="value" placeholder="Input Nilai Point" />
                </template>
                <template v-else-if="column.key === 'catatan'">
                    <a-input v-model:value="value" placeholder="Catatan" />
                </template>
            </template>
      </a-table>
      <!-- perilaku tidak terpuji  -->
      <a-table :dataSource="dataSource4" :columns="kolom4" :pagination="false" bordered>
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
      <a-button
            type="primary"
            style="display: inline-flex; align-items: center"
            @click="writeData"
          >
            <template #icon><save-outlined /></template>Simpan
          </a-button>
      </a-card>
          </a-tab-pane>
        </a-tabs>

    </a-col>
  </a-row>
</template>

<script>
import { message } from 'ant-design-vue';
import { 
    SearchOutlined,
    DownloadOutlined,
    UploadOutlined,
    SaveOutlined
  } from '@ant-design/icons-vue'
import { defineComponent, ref } from 'vue'
import moment from 'moment';
const columns = [
  {
    title: 'No.',
    dataIndex: 'key',
    key: 'no',
    width : 50
  },
  {
    title: 'Tanggal',
    dataIndex: 'Tanggal',
    key: 'tanggal',
    width : 150
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
const dataSource1 = [
  {
    key: '1',
    kegiatanibadah: 'Qiyamul Lail',

  },
  {
    key: '2',
    kegiatanibadah: 'Dhuha',
  },
  {
    key: '3',
    kegiatanibadah: 'Tilawah Quran',
  },
  {
    key: '4',
    kegiatanibadah: 'Membaca Buku',
  },
  {
    key: '5',
    kegiatanibadah: 'Olahraga',
  },
  {
    key: '6',
    kegiatanibadah: 'Al Matsurat',
  },
  {
    key: '7',
    kegiatanibadah: 'Shoum Sunnah',
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
    shalat: 'Shalat Subuh',
  },
  {
    key: '2',
    shalat: 'Shalat Dzuhur',
  },
  {
    key: '3',
    shalat: 'Shalat Ashar',
  },
  {
    key: '4',
    shalat: 'Shalat Maghrib',
  },
  {
    key: '5',
    shalat: 'Shalat Isya',
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
    ibadah: 'Menjadi Imam/Muadzin',
  },
  {
    key: '2',
    ibadah: 'Infaq Terbaik Jumat',
  },
  {
    key: '3',
    ibadah: 'Berbagi makanan dan perlengkapan',
  },
  {
    key: '4',
    ibadah: 'Mengutip dan membuang Sampah pada tempatnya',
  },
  {
    key: '5',
    ibadah: 'Mendamaikan teman yang berselisih',
  },
  {
    key: '6',
    ibadah: 'Menjadi Tentor Sebaya ketika Belajar',
  },
  {
    key: '7',
    ibadah: 'Merapikan Sandal dan sepatu',
  },
  {
    key: '8',
    ibadah: 'Menunaikan Tugas Piket',
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
    aqidah: 'Membawa Jimat (Benda yang dianggap bisa menolong)',
  },
  {
    key: '2',
    aqidah: 'Membawa Buku-buku Zodiak',
  },
  {
    key: '3',
    aqidah: 'Telat sholat berjamaah',
  },
  {
    key: '4',
    aqidah: 'Tidak menjaga adab dalam masjid (doa dan Dzikir)',
  },
  {
    key: '5',
    aqidah: 'Berkelahi',
  },
  {
    key: '6',
    aqidah: 'Mencuri',
  },
  {
    key: '7',
    aqidah: 'Berdusta',
  },
  {
    key: '8',
    aqidah: 'Melakukan perbuatan asusila',
  },
  {
    key: '9',
    aqidah: 'Merokok dan Narkoba',
  },
  {
    key: '10',
    aqidah: 'Berperilaku dan bertutur kata tidak sopan, kasar /Kotor',
  },
  {
    key: '11',
    aqidah: 'Memanfaatkan/ memakai barang milik orang lain tanpa izin',
  },
  {
    key: '12',
    aqidah: 'Membully Teman',
  },
  {
    key: '13',
    aqidah: 'Berpacaran',
  },
  {
    key: '14',
    aqidah: 'Makan sambil berdiri dan atau berjalan',
  },
  {
    key: '15',
    aqidah: 'Tidak Antri dalam setuap Urusan',
  },
  {
    key: '16',
    aqidah: 'Keluar sekolah/ kelas tanpa izin',
  },
  {
    key: '17',
    aqidah: 'Membawa HP',
  },
  {
    key: '18',
    aqidah: 'Membawa perhiasan berharga',
  },
  {
    key: '19',
    aqidah: 'Membawa Uang Jajan',
  },
  {
    key: '20',
    aqidah: 'Merusak peralatan sekolah',
  },
  {
    key: '21',
    aqidah: 'Membawa sepeda motor',
  },
  {
    key: '22',
    aqidah: 'Tidak melaksanakan piket kelas',
  },
  {
    key: '23',
    aqidah: 'Tidak memakai seragam sesuai jadwal',
  },
  {
    key: '24',
    aqidah: 'Berambut dan berkuku Panjang bagi laki-laki',
  },
  {
    key: '25',
    aqidah: 'Membuang Sampah tidak pada tempatnya',
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
      dataSource3,
      kolom3,
      dataSource4,
      kolom4,
      classes: [],
      formTahfidz: {
       tanggal: null,
       surah : 'Al-Fatihah',
        ayat : null

      },
      userData: null,
      tahfidzData: null,
      formIbadah: {
        QiyamulLail : false,
        Dhuha : false,
        TilawahQuran: false,
        MembacaBuku: false,
        Olahraga: false,
        AlMatsurat: false,
        ShoumSunnah: false,
        ShalatSubuh : false,
        ShalatDzuhur: false,
        ShalatAshar: false,
        ShalatMaghrib: false,
        ShalatIsya: false,
        Tanggal: moment().format('YYYY-MM-DD'),
      },
      surahData : [
        {
          key: '1',
          surah: 'Al-Fatihah',
        },
        {
          key: '2',
          surah: 'Al-Baqarah',
        },
        {
          key: '3',
          surah: 'Ali Imran',
        },
        {
          key: '4',
          surah: 'An-Nisa',
        },
        {
          key: '5',
          surah: 'Al-Maidah',
        },
        {
          key: '6',
          surah: 'Al-Anam',
        },
        {
          key: '7',
          surah: 'Al-Araf',
        },
        {
          key: '8',
          surah: 'Al-Anfal',
        },
        {
          key: '9',
          surah: 'At-Taubah',
        },
        {
          key: '10',
          surah: 'Yunus',
        },
        {
          key: '11',
          surah: 'Hud',
        },
        {
          key: '12',
          surah: 'Yusuf',
        },
        {
          key: '13',
          surah: 'Ar-Ra\'d',
        },
        {
          key: '14',
          surah: 'Ibrahim',
        },
        {
          key: '15',
          surah: 'Al-Hijr',
        },
        {
          key: '16',
          surah: 'An-Nahl',
        },
        {
          key: '17',
          surah: 'Al-Isra',
        },
        {
          key: '18',
          surah: 'Al-Kahfi',
        },
        {
          key: '19',
          surah: 'Maryam',
        },
        {
          key: '20',
          surah: 'Ta-Ha',
        },
        {
          key: '21',
          surah: 'Al-Anbiya',
        },
        {
          key: '22',
          surah: 'Al-Hajj',
        },
        {
          key: '23',
          surah: 'Al-Mu\'minun',
        },
        {
          key: '24',
          surah: 'An-Nur',
        },
        {
          key: '25',
          surah: 'Al-Furqan',
        },
        {
          key: '26',
          surah: 'Ash-Shu\'ara',
        },
        {
          key: '27',
          surah: 'An-Naml',
        },
        {
          key: '28',
          surah: 'Al-Qasas',
        },
        {
          key: '29',
          surah: 'Al-Ankabut',
        },
        {
          key: '30',
          surah: 'Ar-Rum',
        },
        {
          key: '31',
          surah: 'Luqman',
        },
        {
          key: '32',
          surah: 'As-Sajdah',
        },
        {
          key: '33',
          surah: 'Al-Ahzab',
        },
        {
          key: '34',
          surah: 'Saba',
        },
        {
          key: '35',
          surah: 'Fatir',
        },
        {
          key: '36',
          surah: 'Ya-Sin',
        },
        {
          key: '37',
          surah: 'As-Saffat',
        },
        {
          key: '38',
          surah: 'Sad',
        },
        {
          key: '39',
          surah: 'Az-Zumar',
        },
        {
          key: '40',
          surah: 'Ghafir',
        },
        {
          key: '41',
          surah: 'Fussilat',
        },
        {
          key: '42',
          surah: 'Ash-Shura',
        },
        {
          key: '43',
          surah: 'Az-Zukhruf',
        },
        {
          key: '44',
          surah: 'Ad-Dukhan',
        },
        {
          key: '45',
          surah: 'Al-Jathiya',
        },
        {
          key: '46',
          surah: 'Al-Ahqaf',
        },
        {
          key: '47',
          surah: 'Muhammad',
        },
        {
          key: '48',
          surah: 'Al-Fath',
        },
        {
          key: '49',
          surah: 'Al-Hujurat',
        },
        {
          key: '50',
          surah: 'Qaf',
        },
        {
          key: '51',
          surah: 'Adh-Dhariyat',
        },
        {
          key: '52',
          surah: 'At-Tur',
        },
        {
          key: '53',
          surah: 'An-Najm',
        },
        {
          key: '54',
          surah: 'Al-Qamar',
        },
        {
          key: '55',
          surah: 'Ar-Rahman',
        },
        {
          key: '56',
          surah: 'Al-Waqi\'ah',
        },
        {
          key: '57',
          surah: 'Al-Hadid',
        },
        {
          key: '58',
          surah: 'Al-Mujadilah',
        },
        {
          key: '59',
          surah: 'Al-Hasyr',
        },
        {
          key: '60',
          surah: 'Al-Mumtahanah',
        },
        {
          key: '61',
          surah: 'As-Saff',
        },
        {
          key: '62',
          surah: 'Al-Jumu\'ah',
        },
        {
          key: '63',
          surah: 'Al-Munafiqun',
        },
        {
          key: '64',
          surah: 'At-Taghabun',
        },
        {
          key: '65',
          surah: 'At-Talaq',
        },
        {
          key: '66',
          surah: 'At-Tahrim',
        },
        {
          key: '67',
          surah: 'Al-Mulk',
        },
        {
          key: '68',
          surah: 'Al-Qalam',
        },
        {
          key: '69',
          surah: 'Al-Haqqah',
        },
        {
          key: '70',
          surah: 'Al-Ma\'arij',
        },
        {
          key: '71',
          surah: 'Nuh',
        },
        {
          key: '72',
          surah: 'Al-Jinn',
        },
        {
          key: '73',
          surah: 'Al-Muzzammil',
        },
        {
          key: '74',
          surah: 'Al-Muddaththir',
        },
        {
          key: '75',
          surah: 'Al-Qiyamah',
        },
        {
          key: '76',
          surah: 'Al-Insan',
        },
        {
          key: '77',
          surah: 'Al-Mursalat',
        },
        {
          key: '78',
          surah: 'An-Naba',
        },
        {
          key: '79',
          surah: 'An-Nazi\'at',
        },
        {
          key: '80',
          surah: '\'Abasa',
        },
        {
          key: '81',
          surah: 'At-Takwir',
        },
        {
          key: '82',
          surah: 'Al-Infitar',
        },
        {
          key: '83',
          surah: 'Al-Mutaffifin',
        },
        {
          key: '84',
          surah: 'Al-Insyiqaq',
        },
        {
          key: '85',
          surah: 'Al-Buruj',
        },
        {
          key: '86',
          surah: 'At-Tariq',
        },
        {
          key: '87',
          surah: 'Al-A\'la',
        },
        {
          key: '88',
          surah: 'Al-Ghashiyah',
        },
        {
          key: '89',
          surah: 'Al-Fajr',
        },
        {
          key: '90',
          surah: 'Al-Balad',
        },
        {
          key: '91',
          surah: 'Ash-Shams',
        },
        {
          key: '92',
          surah: 'Al-Lail',
        },
        {
          key: '93',
          surah: 'Ad-Duha',
        },
        {
          key: '94',
          surah: 'Al-Insyirah',
        },
        {
          key: '95',
          surah: 'At-Tin',
        },
        {
          key: '96',
          surah: 'Al-Alaq',
        },
        {
          key: '97',
          surah: 'Al-Qadr',
        },
        {
          key: '98',
          surah: 'Al-Bayyinah',
        },
        {
          key: '99',
          surah: 'Az-Zalzalah',
        },
        {
          key: '100',
          surah: 'Al-\'Adiyat',
        },
        {
          key: '101',
          surah: 'Al-Qari\'ah',
        },
        {
          key: '102',
          surah: 'At-Takasur',
        },
        {
          key: '103',
          surah: 'Al-\'Asr',
        },
        {
          key: '104',
          surah: 'Al-Humazah',
        },
        {
          key: '105',
          surah: 'Al-Fil',
        },
        {
          key: '106',
          surah: 'Quraisy',
        },
        {
          key: '107',
          surah: 'Al-Ma\'un',
        },
        {
          key: '108',
          surah: 'Al-Kausar',
        },
        {
          key: '109',
          surah: 'Al-Kafirun',
        },
        {
          key: '110',
          surah: 'An-Nasr',
        },
        {
          key: '111',
          surah: 'Al-Lahab',
        },
        {
          key: '112',
          surah: 'Al-Ikhlas',
        },
        {
          key: '113',
          surah: 'Al-Falaq',
        },
        {
          key: '114',
          surah: 'An-Nas',
        },
      ]
    }
  },
  created() {
  this.readData()
  this.readLaporanTahfidz()
  
  },

  components: {
    SearchOutlined,
    DownloadOutlined,
    UploadOutlined,
    SaveOutlined
  },
  methods: {
    onFinish(){
      this.saveLaporanTahfidz()
    },
    saveLaporanTahfidz(){
     this.axios.post(this.url('laporan/tahfidz/write'), 
       this.formTahfidz = {...this.formTahfidz, user_id : this.userData.id}
     ).then(res => {
      this.readData();
      this.resetFormTahfidz()
      this.$notification.success({
                        message: 'Berhasil',
                        description:res.data.message
                    })
   
     }).catch(e => {
      this.$notification.error({
                        message: 'Kesalahan',
                        description: JSON.stringify(e.response.data.errors)
                    })
     })
    },
    readData(){
      this.axios.get(this.url('user')).then(res => {
        this.userData = res.data
        const params = {
          user_id : this.userData.id,
          req : 'single'
        }
        this.axios.get(this.url('laporan/tahfidz/read'), { params} ).then(res => {
          this.tahfidzData = res.data
         console.log( this.tahfizSource());
         
        })
      })
    },
    resetFormTahfidz(){
     this.formTahfidz.tanggal = null,
     this.formTahfidz.surah = 'Al-Fatihah',
     this.formTahfidz.ayat = null
      },
    tahfizSource(){
    return  this.tahfidzData.data.map((item, index) => {
        return {
          key: index + 1,
         tanggal: moment(item.tanggal).format('DD MMMM YYYY'),
         surah: item.surah,
         ayat: item.ayat_start + ' - ' + item.ayat_end,
        }
      })
  },
  resetFormIbadah(){
  
        this.formIbadah.QiyamulLail =  false
        this.formIbadah.Dhuha = false
        this.formIbadah.TilawahQuran= false
        this.formIbadah.MembacaBuku= false
        this.formIbadah.Olahraga= false
        this.formIbadah.AlMatsurat= false
        this.formIbadah.ShoumSunnah= false
        this.formIbadah.ShalatSubuh = false
        this.formIbadah.ShalatDzuhur= false
        this.formIbadah.ShalatAshar= false
        this.formIbadah.ShalatMaghrib= false
        this.formIbadah. ShalatIsya= false
        this.formIbadah.Tanggal= moment().format('YYYY-MM-DD')
      
  },
  saveMutabaahYaumiah(){
    console.log(this.formIbadah);
    this.formIbadah = {...this.formIbadah, user_id : this.userData.id}
    this.axios.post(this.url('laporan/mutabaah-yaumiyah/write'), this.formIbadah).then((res) => {
      this.$notification.success({
                        message: 'Berhasil',
                        description:res.data.message
                    })
    this.resetFormIbadah();
    window.scrollTo(0, 0);
                  
    }).catch((e) => {
      console.log(e.response.data.errors);
      this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: JSON.stringify(e.response.data.errors)
                    })
    })
  }
  },

}
</script>