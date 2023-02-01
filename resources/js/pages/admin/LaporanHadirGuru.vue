<template>
    <h1 style="margin-left: 30px">Laporan Kehadiran Guru</h1>
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
            <a-date-picker
              v-model:value="filter.tanggal"
              style="width: 345px"
              placeholder="Tanggal"
            />
          </a-space>
          <a-space
            direction="horizontal"
            style="display: flex; justify-content: flex-end"
          >
            <a-button
              type="primary"
              style="display: inline-flex; align-items: center"
              @click.prevent="readData"
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
            <a-button
              type="primary"
              style="display: inline-flex; align-items: center"
              @click="writeData"
            >
              <template #icon><save-outlined /></template>Simpan Kehadiran
            </a-button>
          </a-space>
          <a-table :columns="columns" :data-source="models" style="margin: 15px">
            <template #bodyCell="{ column, record }">
              <template v-if="column.key === 'nama'">
                <a>
                  {{ record.nama }}
                </a>
              </template>
              <template v-else-if="column.key === 'kehadiran'">
                <div>
                  <a-radio-group
                    v-model:value="form[`siswa-${record.id}-`].status"
                  >
                    <a-radio value="hadir">Hadir</a-radio>
                    <a-radio value="absen">Absen</a-radio>
                    <a-radio value="telat">Telat</a-radio>
                    <a-radio value="izin">Izin</a-radio>
                  </a-radio-group>
                </div>
              </template>
              <template v-else-if="column.key === 'description'">
                <a-input
                  v-model:value="form[`siswa-${record.id}-`].description"
                  placeholder="keterangan"
                />
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
      title: 'ID',
      dataIndex: 'id'
    },
    {
      title: 'NIS',
      dataIndex: ['user', 'no_induk']
    },
    {
      title: 'Nama Siswa',
      dataIndex: ['user', 'name']
    },
    {
      title: 'Kehadiran',
      key: 'kehadiran'
    },
    {
      title: '',
      key: 'description'
    }
  ]
  const data = [
    {
      key: '1',
      nis: 2910398212,
      nama: 'Muhammad Farhan Syahreza'
    },
    {
      key: '2',
      nis: 2910398212,
      nama: 'Muhammad Farhan Syahreza'
    },
    {
      key: '3',
      nis: 2910398212,
      nama: 'Muhammad Farhan Syahreza'
    }
  ]
  export default {
    data() {
      return {
        data,
        models: [],
        form: {},
        columns,
        classes: [],
        filter: {
          kelas_id: null,
          tanggal: null
        },
        dataReady: false,
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
        vm.fetching = true
        vm.dataReady = false
        const params = {
          req: 'daily',
          kelas_id: vm.filter.kelas_id,
          tanggal: vm.getDate(vm.filter.tanggal)
        }
        vm.axios
          .get(vm.url('kehadiran/read'), { params })
          .then((response) => {
            vm.fetching = false
            console.log(response.data.models)
            vm.models = response.data.models
            vm.models.forEach((kehadiran) => {
              console.log(kehadiran)
              vm.form[`siswa-${kehadiran.id}-`] = {
                status: kehadiran.kehadiran
                  ? kehadiran.kehadiran.kehadiran
                  : null,
                description: kehadiran.kehadiran
                  ? kehadiran.kehadiran.keterangan
                  : null
              }
            })
          })
          .then(() => (vm.dataReady = true))
          .catch((e) => {
            vm.$onAjaxError(e)
            vm.dataReady = false
          })
      },
      writeData() {
        const vm = this
        const params = {
          req: 'write',
          kehadiran: vm.form,
          ...vm.filter
        }
        vm.axios
          .post(vm.url('kehadiran/write'), params)
          .then(() => {
            vm.readData()
            vm.openNotification('berhasil update data', 'success')
          })
          .catch((e) => (vm.validation = vm.$onAjaxError(e)))
      },
      getDate(date) {
        let tanggal = new Date(date)
        var dd = String(tanggal.getDate()).padStart(2, '0')
        var mm = String(tanggal.getMonth() + 1).padStart(2, '0') //January is 0!
        var yyyy = tanggal.getFullYear()
        return yyyy + '-' + mm + '-' + dd
      }
    }
  }
  </script>
  