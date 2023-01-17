<template>
  <h1 style="margin-left: 30px">Laporan Penilaian Siswa</h1>
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
            v-model:value="filter.kelas_id"
            style="width: 345px"
            placeholder="Kelas"
          >
            <a-select-option
              v-for="cls in classes"
              :key="cls.id"
              :value="cls.id"
              >{{ cls.jenjang }} - {{ cls.section }}</a-select-option
            >
          </a-select>
          <a-select
            v-model:value="filter.semester"
            style="width: 256px"
            placeholder="Semester"
          >
            <a-select-option value="ganjil">Ganjil</a-select-option>
            <a-select-option value="genap">Genap</a-select-option>
          </a-select>
        </a-space>
        <a-space
          direction="horizontal"
          style="display: flex; justify-content: flex-end"
        >
          <a-button
            type="primary"
            style="display: inline-flex; align-items: center"
            @click="checkAndFetch"
          >
            <template #icon><SearchOutlined /></template>
            Cari
          </a-button>
        </a-space>
      </a-card>
      <a-card
        v-if="dataReady"
        :loading="fetching"
        title="Daftar Siswa"
        style="width: 100%"
      >
        <a-space
          direction="horizontal"
          style="display: flex; justify-content: flex-end; margin-bottom: 20px"
        >
          <a-input-search
            v-model:value="filter.search"
            placeholder="Cari Siswa"
            style="width: 200px"
            @search="readData"
          />
          <a-button>
            <template #icon><download-outlined /></template>
          </a-button>
        </a-space>
        <a-table :columns="columns" :data-source="data" style="margin: 15px">
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
                  <a-radio :value="5">Absen dengan Keterangan</a-radio>
                </a-radio-group>
              </div>
            </template>
            <template v-else-if="column.key === 'action'">
              <span>
                <a-button type="" :size="size">
                  <template #icon>
                    <edit-outlined :style="{ color: '#000000' }" />
                  </template>
                </a-button>
              </span>
            </template>
          </template>
        </a-table>
      </a-card>
    </a-col>
  </a-row>
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
  },
  {
    title: 'Nilai Harian',
    dataIndex: 'nilai_harian',
    key: 'nilai_hariann'
  },
  {
    title: 'Nilai Tugas',
    dataIndex: 'nilai_tugas',
    key: 'nilai_tugas'
  },
  {
    title: 'Nilai Ujian Mid',
    dataIndex: 'nilai_ujian_mid',
    key: 'nilai_ujian_mid'
  },
  {
    title: 'Nilai Ujian Semester',
    dataIndex: 'nilai_ujian_semester',
    key: 'nilai_ujian_semester'
  },
  {
    title: 'Aksi',
    key: 'action'
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
      filter: {
        kelas_id: null,
        pelajaran: null,
        search: null,
        semester: null
      },
      fetching: false
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
    checkAndFetch() {
      if (this.filter.kelas_id && this.filter.semester) {
        this.readData()
      }
    }
  }
}
</script>
