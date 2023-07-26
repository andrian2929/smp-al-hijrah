<template>
    <a-row type="flex" justify="center" :gutter="[8, 8]">
        <a-col
            v-for="hari in [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu'
            ]"
            :key="hari"
            :xl="24"
            :xxl="12"
        >
            <p style="text-align: center">
                <strong>{{ hari.toUpperCase() }}</strong>
            </p>
            <a-table
                :pagination="false"
                size="middle"
                :columns="
                    editMode
                        ? [
                              {
                                  title: 'Waktu Mulai',
                                  dataIndex: 'waktu_mulai'
                              },
                              {
                                  title: 'Waktu Selesai',
                                  dataIndex: 'waktu_selesai'
                              },
                              {
                                  title: 'Mata Pelajaran',
                                  dataIndex: ['mapel', 'name']
                              },
                              { title: 'Guru', dataIndex: ['guru', 'name'] },
                              { title: 'Aksi', key: 'operation' }
                          ]
                        : [
                              {
                                  title: 'Waktu Mulai',
                                  dataIndex: 'waktu_mulai'
                              },
                              {
                                  title: 'Waktu Selesai',
                                  dataIndex: 'waktu_selesai'
                              },
                              {
                                  title: 'Mata Pelajaran',
                                  dataIndex: ['mapel', 'name']
                              },
                              { title: 'Guru', dataIndex: ['guru', 'name'] }
                          ]
                "
                :data-source="mapelHari(hari) || []"
            >
                <template v-if="editMode" #summary>
                    <a-table-summary :fixed="'bottom'">
                        <a-table-summary-row>
                            <a-table-summary-cell :index="0">
                                <a-time-picker
                                    v-model:value="form[hari].waktu_mulai"
                                    format="HH:mm"
                                    value-format="HH:mm"
                                    placeholder="Waktu mulai"
                                />
                            </a-table-summary-cell>
                            <a-table-summary-cell :index="0">
                                <a-time-picker
                                    v-model:value="form[hari].waktu_selesai"
                                    format="HH:mm"
                                    value-format="HH:mm"
                                    placeholder="Waktu selesai"
                                />
                            </a-table-summary-cell>
                            <a-table-summary-cell :index="1">
                                <a-select
                                    v-model:value="form[hari].mapel"
                                    placeholder="Pilih mata pelajaran"
                                    style="width: 100%"
                                >
                                    <a-select-option
                                        v-for="mapel in mapelOptions"
                                        :key="mapel.id"
                                        :value="mapel.id"
                                        >{{ mapel.name }}</a-select-option
                                    >
                                </a-select>
                            </a-table-summary-cell>
                            <a-table-summary-cell :index="2">
                                <a-select
                                    v-model:value="form[hari].guru"
                                    placeholder="Pilih guru"
                                    style="width: 100%"
                                >
                                    <a-select-option
                                        :disabled="!form[hari].mapel"
                                        v-for="guru in filterGuruOptions(
                                            form[hari].mapel
                                        )"
                                        :key="guru.id"
                                        :value="guru.id"
                                        >{{ guru.name }}</a-select-option
                                    >
                                </a-select>
                            </a-table-summary-cell>
                            <a-table-summary-cell :index="3">
                                <a-space direction="vertical">
                                    <a-button
                                        type="primary"
                                        @click="addMapel(hari)"
                                        >{{
                                            form[hari].id ? 'Edit' : 'Tambah'
                                        }}</a-button
                                    >
                                    <a-button
                                        v-if="form[hari].id"
                                        @click="() => (form[hari] = {})"
                                        ><clear-outlined
                                    /></a-button>
                                </a-space>
                            </a-table-summary-cell>
                        </a-table-summary-row>
                    </a-table-summary>
                </template>
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key == 'operation'">
                        <a-space direction="horizontal">
                            <a-button
                                warning
                                title="edit"
                                @click="readDataSingle(hari, record)"
                            >
                                <template #icon><form-outlined /></template>
                            </a-button>
                            <a-button
                                danger
                                @click="alert(record.id, removeMapel)"
                            >
                                <template #icon><delete-outlined /></template>
                            </a-button>
                        </a-space>
                    </template>
                </template>
            </a-table>
        </a-col>
    </a-row>
</template>

<script>
import dayjs from 'dayjs'
export default {
    props: {
        kelasId: Number,
        editMode: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            models: [],
            form: {
                senin: {
                    id: null,
                    waktu_mulai: null,
                    waktu_selesai: null,
                    mapel: null,
                    guru: null
                },
                selasa: {
                    id: null,
                    waktu_mulai: null,
                    waktu_selesai: null,
                    mapel: null,
                    guru: null
                },
                rabu: {
                    id: null,
                    waktu_mulai: null,
                    waktu_selesai: null,
                    mapel: null,
                    guru: null
                },
                kamis: {
                    id: null,
                    waktu_mulai: null,
                    waktu_selesai: null,
                    mapel: null,
                    guru: null
                },
                jumat: {
                    id: null,
                    waktu_mulai: null,
                    waktu_selesai: null,
                    mapel: null,
                    guru: null
                },
                sabtu: {
                    id: null,
                    waktu_mulai: null,
                    waktu_selesai: null,
                    mapel: null,
                    guru: null
                }
            },
            mapelOptions: []
        }
    },
    mounted() {
        this.readData()
        this.getMapelOptions()
    },
    methods: {
        readData() {
            const vm = this
            vm.loading = true
            const params = {
                req: 'get_roster',
                kelas_id: vm.$props.kelasId
            }
            vm.axios
                .get(vm.url('kelas/read'), { params: params })
                .then((response) => {
                    vm.models = response.data.models
                    vm.models.waktu_mulai = dayjs(
                        response.data.models.waktu_mulai,
                        'HH:mm'
                    )
                    vm.models.waktu_selesai = dayjs(
                        response.data.models.waktu_selesai,
                        'HH:mm'
                    )
                    vm.loading = false
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        readDataSingle(hari, data) {
            this.form[hari].id = data.id
            this.form[hari].waktu_mulai = data.waktu_mulai
            this.form[hari].waktu_selesai = data.waktu_selesai
            this.form[hari].mapel = data.mapel_id
            this.form[hari].guru = data.guru_id
        },
        mapelHari(hari) {
            let mapels = this.models.filter((mapel) => mapel.hari == hari)

            let newMapel = mapels.map((mapel) => {
                mapel.waktu_mulai = mapel.waktu_mulai.substring(0, 5)
                mapel.waktu_selesai = mapel.waktu_selesai.substring(0, 5)
                return mapel
            })

            return newMapel.sort((a, b) => {
                if (a.waktu_mulai < b.waktu_mulai) return -1
                if (a.waktu_selesai < b.waktu_selesai) return -1
                if (a.waktu_mulai > b.waktu_mulai) return 1
                if (a.waktu_selesai > b.waktu_selesai) return 1
                return 0
            })
        },
        addMapel(hari) {
            const vm = this
            const params = {
                req: 'add_mapel_to_roster',
                hari: hari,
                ...vm.form[hari],
                kelas_id: vm.$props.kelasId
            }
            vm.axios
                .post(vm.url('mapel/write'), params)
                .then((response) => {
                    vm.readData()
                    vm.form[hari] = {}
                })
                .catch((error) => {
                    this.$notification.error({
                        message: 'Terjadi kesalahan',
                        description: error.response.data.message
                    })
                })
        },
        removeMapel(_id) {
            const vm = this
            const params = {
                req: 'remove_mapel_hari',
                id: _id
            }
            vm.axios
                .post(vm.url('kelas/write'), params)
                .then(() => {
                    vm.readData()
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        getMapelOptions() {
            const vm = this
            const params = {
                req: 'all'
            }
            vm.axios
                .get(vm.url('mapel/read'), { params: params })
                .then((response) => {
                    vm.mapelOptions = response.data.models
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        getGuruOptions() {
            const vm = this
            const params = {
                req: ''
            }
        },
        filterGuruOptions(mapel_id) {
            const mapel = this.mapelOptions.find((obj) => obj.id === mapel_id)
            return mapel ? mapel.gurus_user : []
        }
    }
}
</script>
