<template>
    <a-row type="flex" justify="center" :gutter="[8, 8]">
        <a-col :xs="23" :sm="5">
            <a-card>
                <template #cover>
                    <img alt="profile photo" src="/img/no_profile.png" />
                </template>
                <a-card-meta
                    v-if="models.wali_kelas"
                    :title="models.wali_kelas.name"
                >
                    <template #description>{{
                        models.wali_kelas.no_induk
                    }}</template>
                </a-card-meta>
            </a-card>
        </a-col>
        <a-col :xs="23" :sm="18">
            <a-card
                :loading="loading"
                style="width: 100%"
                :tab-list="[
                    { key: 'siswa', tab: 'Siswa' },
                    { key: 'roster', tab: 'Roster' }
                ]"
                :active-tab-key="tab"
                @tabChange="(key) => (tab = key)"
            >
                <template v-if="tab === 'siswa'">
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
                            placeholder="Cari data"
                            @search="getSiswa"
                        />
                        <a-button type="primary" @click="() => (modalAdd = true)"
                            >Tambah</a-button
                        >
                    </a-space>
                    <a-table
                        size="middle"
                        :columns="[
                           {title : 'No', dataIndex : 'number' },
                            {
                                title: 'Nama Siswa',
                                dataIndex: ['user', 'name']
                            },
                            { title: 'NISN', dataIndex: 'nisn' },
                            { title: 'Aksi', key: 'operation' }
                        ]"
                        :data-source="students"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key == 'operation'">
                                <a-button
                                    danger
                                    @click="alert(record.id, removeSiswa)"
                                >
                                    <template #icon
                                        ><delete-outlined
                                    /></template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                </template>
                <template v-else-if="tab === 'roster'">
                    <kelola-kelas-roster
                        :kelas-id="kelas_id"
                        :editMode="true"
                    />
                </template>
            </a-card>
        </a-col>
    </a-row>
    <a-modal v-model:visible="modalAdd" title="Assign Siswa" @ok="assignSiswa">
        <a-form
            :label-col="{ span: 6 }"
            :wrapper-col="{ span: 18 }"
            autocomplete="off"
        >
            <a-form-item label="Siswa" data-column="siswa" required>
                <a-select
                    v-model:value="form.siswa"
                    style="width: 100%"
                    show-search
                    placeholder="Select a person"
                    :filter-option="filterOption"
                    @change="handleChange"
                    @search="searchOptions"
                >
                    <a-select-option
                        v-for="siswa in siswaOptions"
                        :key="siswa.id"
                        :label="siswa.user.name"
                        :value="siswa.id"
                        >{{ siswa.user.name }}</a-select-option
                    >
                </a-select>
            </a-form-item>
        </a-form>
    </a-modal>
  
</template>

<script>
import debounce from 'lodash'
import KelolaKelasRoster from './KelolaKelasRoster.vue'
export default {
    components: { KelolaKelasRoster },
    data() {
        return {
            models: { wali_kelas: {} },
            tab: 'siswa',
            students: [],
            siswaOptions: [],
            roster: [],
            filter: { siswa: null },
            modalAdd: false,
            form: { siswa: null },
          
        }
    },
    mounted() {
        this.readData()
        this.getSiswa()
        this.getSiswaOptions()
    
      
      
    },

    
    computed: {
        kelas_id() {
            return this.$route.params.id
        },
   
    },
    methods: {
        readData() {
            const vm = this
            vm.loading = true
            const params = {
                req: 'single',
                id: vm.$route.params.id
            }

            vm.axios
                .get(vm.url('kelas/read'), { params: params })
                .then((response) => {
                    console.log(response)
                    vm.models = response.data.models
                    vm.loading = false
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        getSiswa() {
            const vm = this
            vm.loading = true
            const params = {
                req: 'get_siswa',
                id: vm.$route.params.id,
                ...vm.filter
            }
            vm.axios
                .get(vm.url('kelas/read'), { params: params })
                .then((response) => {
                  
                    response.data.models.forEach((item, index) => {
                        item.number = index + 1
                    });
                    vm.students = response.data.models
             
                    vm.loading = false
                })
                .catch((error) => vm.$onAjaxError(error))
        },
        removeSiswa(_id) {
            const vm = this
            vm.loading = true
            const params = {
                req: 'remove_siswa',
                id: _id
            }
            vm.axios
                .post(vm.url('kelas/write'), params)
                .then(() => {
                    vm.getSiswa()
                    vm.getSiswaOptions()
                })
                .catch((error) => vm.$onAjaxError(error))
            vm.loading = false
        },
        assignSiswa() {
            const vm = this
            vm.loading = true
            const params = {
                req: 'assign_siswa',
                kelas_id: vm.$route.params.id,
                siswa_id: vm.form.siswa
            }
            vm.axios
                .post(vm.url('kelas/write'), params)
                .then(() => {
                    vm.getSiswa()
                    vm.modalAdd = false
                    vm.form = {}
                    vm.getSiswaOptions()
                })
                .catch((error) => (vm.validation = vm.$onAjaxError(error)))
            vm.loading = false
        },
        getSiswaOptions(key = null) {
            const vm = this
            const params = {
                req: 'get_siswa_options',
                search: key
            }
            vm.axios
                .get(vm.url('kelas/read'), { params: params })
                .then((response) => {
                    vm.siswaOptions = response.data.models
                })
                .catch((error) => vm.$onAjaxError(error))
        },
        searchOptions(input) {
            debounce(this.getSiswaOptions(input))
        },
        filterOption(input, option) {
            return this.siswaOptions
        }
    }
}
</script>
