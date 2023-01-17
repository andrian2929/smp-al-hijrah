<template>
  <a-row type="flex" justify="center">
    <a-col :xs="23">
      <a-card :loading="loading" title="INFORMASI SISWA" style="width: 100%">
        <a-space
          direction="horizontal"
          style="display: flex; justify-content: flex-end; margin-bottom: 20px"
        >
          <a-select
            v-model:value="filters.kelas_id"
            style="width: 120px"
            placeholder="--kelas--"
            @change="(e) => (filters.kelas_id = e.value)"
          >
            <a-select-option value="VII">VII</a-select-option>
            <a-select-option value="VIII">VIII</a-select-option>
            <a-select-option value="IX">IX</a-select-option>
          </a-select>
          <a-input-search
            v-model:value="filters.search"
            placeholder="cari data"
            @search="readData"
          />
          <router-link to="/admin/kelola_akun/new">
            <a-button>Tambah</a-button>
          </router-link>
        </a-space>
        <a-table :columns="columns" :data-source="models" style="margin: 15px">
          <template #bodyCell="{ column, record }">
            <template v-if="column.key == 'kelas'">
              <span
                >{{ record.kelas.jenjang }} - {{ record.kelas.section }}</span
              >
            </template>
            <template v-if="column.key == 'is_beasiswa'">
              <span v-if="record.is_beasiswa"><check-outlined /></span>
            </template>
            <template v-if="column.key === 'action'">
              <span>
                <router-link :to="`/admin/info_siswa/${record.id}`">
                  <a-button style="margin-left: 3px" type="primary">
                    <template #icon>
                      <eye-outlined :style="{ color: '#ffffff' }" />
                    </template>
                  </a-button>
                </router-link>
              </span>
            </template>
          </template>
        </a-table>
      </a-card>
    </a-col>
  </a-row>
</template>
<script>
import { defineComponent } from 'vue';
import {
  CheckOutlined,
} from '@ant-design/icons-vue';
export default defineComponent({
    components: {
        CheckOutlined
    },
  data() {
    const columns = [
      {
        title: 'ID',
        dataIndex: 'id'
      },
      {
        title: 'Nama Siswa',
        dataIndex: ['user', 'name']
      },
      {
        title: 'Kelas',
        key: 'kelas'
      },
      {
        title: 'NIS',
        dataIndex: ['user', 'no_induk']
      },
      {
        title: 'Beasiswa',
        key: 'is_beasiswa'
      },
      {
        title: '',
        key: 'action'
      }
    ]
    const data = [
      {
        key: '1',
        nama: 'Muhammad Farhan Syahreza',
        kelas: 'VII A',
        nis: 2910398212,
        beasiswa: 'Tidak'
      },
      {
        key: '2',
        nama: 'Muhammad Farhan Syahreza',
        kelas: 'VII A',
        nis: 2910398212,
        beasiswa: 'Ya'
      },
      {
        key: '3',
        nama: 'Muhammad Farhan Syahreza',
        kelas: 'VII A',
        nis: 2910398212,
        beasiswa: 'Tidak'
      }
    ]
    return {
      columns,
      data,
      models: [],
      filters: {
        search: null,
        kelas_id: null
      },
      loading: false
    }
  },
  mounted() {
    this.readData()
  },
  watch: {
    'filters.kelas_id'() {
      this.readData()
    }
  },
  methods: {
    readData() {
      const vm = this
      vm.loading = true
      const params = {
        req: 'table',
        ...vm.filters
      }
      vm.axios
        .get(vm.url('siswa/read'), { params: params })
        .then((response) => {
          vm.loading = false
          vm.models = response.data.models
        })
        .catch((e) => vm.$onAjaxError(e))
    },
    deleteData(_id) {
      const vm = this
      const params = {
        req: 'delete',
        id: _id
      }
      vm.axios
        .post(vm.url('user/write'), params)
        .then(() => {
          vm.openNotification('data berhasil terhapus', 'success')
          vm.readData()
        })
        .catch((e) => (vm.validation = vm.$onAjaxError(e)))
    }
  }
});
</script>
