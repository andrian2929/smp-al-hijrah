<template>
<h1 style="margin-left: 30px">Tugas Siswa</h1>
  <a-row type="flex" justify="center">
    <a-col :xs="23">
      <a-card
        :loading="loading"
        title="Pilih Kriteria"
        style="width: 100%; margin-bottom: 20px"
      >
        <a-space
          style="display: flex;
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
          <a-select
                v-model:value="filter.subjek"
                style="width: 345px"
                placeholder="Subjek"
                @change="(e) => (filter.subjek = e.value)"
            >
                <a-select-option value="ips">IPS</a-select-option>
                <a-select-option value="ipa">IPA</a-select-option>
                <a-select-option value="bahasa">BAHASA</a-select-option>
            </a-select>
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
        :loading="fetching"
        title="Status Tugas Siswa"
        style="width: 100%"
      >
        <a-space
          direction="horizontal"
          style="display: flex; justify-content: flex-end; margin-bottom: 20px"
        >
          <!-- <a-button
            style="display: inline-flex; align-items: center"
            type="primary"
            @click="showModal"
          >
            <template #icon><plus-outlined /></template>Tambah
          </a-button> -->
          <template #extra>
            <a
                @click="
                    () => {
                        modal.tambah = true
                        editPart = 'tambah'
                    }
                "
                ><template #icon><plus-outlined /></template>Tambah
              </a>
          </template>
        </a-space>
        <a-table :columns="columns" :data-source="models" style="margin: 15px">
          <template #bodyCell="{ column, record }">
            <template v-if="column.key === 'nama'">
              <a>
                {{ record.nama }}
              </a>
            </template>
            <template v-else-if="column.key === 'status'">
                <a-select
                v-model:value="filter.status"
                style="width: 150px"
                placeholder="Status"
                @change="(e) => (filter.status = e.value)"
            >
                <a-select-option value="selesai">Selesai</a-select-option>
                <a-select-option value="belum">Belum Selesai</a-select-option>
            </a-select>
            </template>
            <template v-else-if="column.key === 'judul'">
                <a>
                {{ record.judul }}
              </a>
            </template>
            <template v-else-if="column.key === 'skor'">
              <a-input
                v-model:value="form[`siswa-${record.id}-`].skor"
                placeholder="Skor"
              />
            </template>
          </template>
        </a-table>
      </a-card>
    </a-col>
  </a-row>
  <!-- <a-modal v-model="visible" title="Buat Tugas Siswa" @ok="handleOk" width="1000px">
    <a-row>
    <a-select
            v-model:value="filter.kelas_id"
            style="width: 345px; margin-bottom: 6px; margin-right: 6px"
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
                v-model:value="filter.subjek"
                style="width: 345px; margin-bottom: 6px; margin-right: 6px"
                placeholder="Subjek"
                @change="(e) => (filter.subjek = e.value)"
            >
                <a-select-option value="ips">IPS</a-select-option>
                <a-select-option value="ipa">IPA</a-select-option>
                <a-select-option value="bahasa">BAHASA</a-select-option>
            </a-select>
          </a-row>
          <a-row>
            <a-date-picker
              v-model:value="filter.tanggal"
              style="width: 345px; margin-bottom: 6px; margin-right: 6px"
              placeholder="Tanggal PR"
            />
            <a-date-picker
              v-model:value="filter.tanggal"
              style="width: 345px; margin-bottom: 6px; margin-right: 6px"
              placeholder="Tanggal Pengumpulan"
            />
            <a-upload
          v-model:file-list="fileList"
          name="file"
          action="https://www.mocky.io/v2/5cc8019d300000980a055e76"
          :headers="headers"
          @change="handleChange"
        >
          <a-button>
            <upload-outlined></upload-outlined>
            Attach Document
          </a-button>
          </a-upload>
          </a-row>
          <a-row style="margin-bottom: 6px">
            <a-input v-model:value="value" placeholder="Input Nilai Point" style="width: 345px; margin-right: 6px"/>
            <a-input v-model:value="value" placeholder="Judul" style="width: 345px;"/>
          </a-row>
          <a-row>
            <a-textarea v-model:value="value2" placeholder="Deskripsi" allow-clear />
          </a-row>
  </a-modal> -->
  <a-modal v-if="editMode"
        v-model:visible="modal.tambah"
        title="Tambah"
        @ok="writeData">
        <a-form
            name="tmabah"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 16 }"
            class="login-form"
            autocomplete="off"
        >
            <a-form-item
                label="Nomor Telepon"
                name="no_telp"
                :class="{ 'ant-form-item-has-error': validation.no_telp }"
            >
                <a-input v-model:value="form.no_telp" />
            </a-form-item>

            <a-form-item
                label="Email"
                name="email"
                :class="{ 'ant-form-item-has-error': validation.email }"
            >
                <a-input v-model:value="form.email" />
            </a-form-item>

        </a-form>
    </a-modal>
</template>

<script>
import { message } from 'ant-design-vue';
import { 
    SearchOutlined,
    PlusOutlined,
    UploadOutlined
  } from '@ant-design/icons-vue'
import { defineComponent, ref } from 'vue'
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
    title: 'Tanggal Pengumpulan',
    key: 'tanggalpengumpulan'
  },
  {
    title: 'Status',
    key: 'status'
  },
  {
    title: 'Judul',
    key: 'judul'
  },
  {
    title: 'Skor',
    key: 'skor'
  }
]
const data = [
  {
    key: '1',
    nis: 2910398212,
    nama: 'Muhammad Farhan Syahreza',
    tanggalpengumpulan : '2022-12-12',
  }
]
export default {
  props: {
    editMode: {
            type: Boolean,
            default: false
        }
  },
  setup() {
    const value2 = ref('');
    // const handleChange = info => {
    //   if (info.file.status !== 'uploading') {
    //     console.log(info.file, info.fileList);
    //   }
    //   if (info.file.status === 'done') {
    //     message.success(`${info.file.name} file uploaded successfully`);
    //   } else if (info.file.status === 'error') {
    //     message.error(`${info.file.name} file upload failed.`);
    //   }
    // };
    const fileList = ref([]);
    return {
      value2,
      fileList,
      headers: {
        authorization: 'authorization-text',
      },
      handleChange,
    };
  },
   components: {
    SearchOutlined,
    PlusOutlined,
    UploadOutlined
  },
  data() {
    return {
      data,
      models: [],
      form: {},
      modal: {
        tambah: false
      },
      columns,
      classes: [],
      filter: {
        kelas_id: null,
        tanggal: null
      },
      dataReady: false,
      fetching: false
    };
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
        // kelas_id: vm.filter.kelas_id,
        // tanggal: vm.getDate(vm.filter.tanggal)
      }
      vm.axios
        // .get(vm.url('kehadiran/read'), { params })
        .then((response) => {
          vm.fetching = false
          console.log(response.data.models)
          vm.models = response.data.models
          vm.models.forEach((kehadiran) => {
            console.log(kehadiran)
            // vm.form[`siswa-${kehadiran.id}-`] = {
            //   status: kehadiran.kehadiran
            //     ? kehadiran.kehadiran.kehadiran
            //     : null,
            //   description: kehadiran.kehadiran
            //     ? kehadiran.kehadiran.keterangan
            //     : null
            // }
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
        kehadiran: vm.editPart,
        ...vm.form
      }
      vm.axios
      .then(() => {
        vm.modal = {
          tambah: false,
        }
      })
      // vm.axios
        // .post(vm.url('kehadiran/write'), params)
        // .then(() => {
        //   vm.readData()
        //   vm.openNotification('berhasil update data', 'success')
        // })
        // .catch((e) => (vm.validation = vm.$onAjaxError(e)))
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
