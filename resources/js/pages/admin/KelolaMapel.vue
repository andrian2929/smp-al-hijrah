<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="TATA KELOLA MATA PELAJARAN"
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
                    <a-button @click="newData">Tambah</a-button>
                </a-space>
                <a-table :columns="columns" :data-source="mapels" size="middle">
                    <template #bodyCell="{ column, record }">
                        <template
                            v-if="column.key === 'guru'"
                            v-for="guru in record.gurus_user"
                        >
                            <ul>
                                <li>{{ guru.name }}</li>
                            </ul>
                        </template>
                        <template v-if="column.key === 'operation'">
                            <a-button
                                warning
                                @click="readDataSingle(record.id)"
                            >
                                <template #icon><form-outlined /></template>
                            </a-button>
                            <a-button
                                danger
                                @click="alert(record.id, deleteData)"
                            >
                                <template #icon><delete-outlined /></template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>
    <a-modal v-model:visible="modalShow" :title="modalTitle" @ok="writeData">
        <a-form
            :label-col="{ span: 6 }"
            :wrapper-col="{ span: 18 }"
            autocomplete="off"
        >
            <a-form-item label="Mata Pelajaran" required>
                <a-input
                    v-model:value="form.name"
                    placeholder="Nama Mata Pelajaran"
                />
            </a-form-item>
            <a-form-item label="Deskripsi">
                <a-input
                    v-model:value="form.description"
                    placeholder="Deskripsi Mata Pelajaran"
                />
            </a-form-item>
            <a-form-item label="KKM">
                <a-input-number
                    v-model:value="form.kkm"
                    placeholder="Nilai KKM"
                    :min="1"
                    :max="100"
                />
            </a-form-item>
            <a-form-item label="Jenjang" required>
                <a-select v-model:value="form.jenjang" style="width: 100%">
                    <a-select-option value="VII">VII</a-select-option>
                    <a-select-option value="VIII">VIII</a-select-option>
                    <a-select-option value="IX">IX</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item label="Guru Pengajar">
                <a-select
                    v-model:value="form.gurus"
                    mode="multiple"
                    max-tag-count="5"
                    placeholder="Pilih Guru Pengajar"
                    style="width: 100%"
                    :filterOption="
                        (input, option) => {
                            return (
                                option.label
                                    .toLowerCase()
                                    .indexOf(input.toLowerCase()) >= 0
                            )
                        }
                    "
                >
                    <a-select-option
                        v-for="guru in guruOptions"
                        :key="guru.id"
                        :label="guru.name"
                        :value="guru.id"
                    >
                        {{ guru.name }}
                    </a-select-option>
                </a-select>
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script>
import { computed, ref } from 'vue'
export default {
    data() {
        const columns = [
            {
                title: 'Kode Mapel',
                dataIndex: 'id'
            },
            {
                title: 'Mata Pelajaran',
                dataIndex: 'name',
                sorter: (a, b) => a.name.localeCompare(b.name),
                sortDirections: ['ascend', 'descend']
            },
            {
                title: 'Jenjang Kelas',
                dataIndex: 'jenjang',
                filters: [
                    {
                        text: 'VII',
                        value: 'VII'
                    },
                    {
                        text: 'VIII',
                        value: 'VIII'
                    },
                    {
                        text: 'IX',
                        value: 'IX'
                    }
                ],
                filterMultiple: false,
                onFilter: (value, record) => record.jenjang.indexOf(value) === 0
            },
            {
                title: 'Guru Pengajar',
                key: 'guru'
            },
            {
                title: 'action',
                key: 'operation'
            }
        ]
        return {
            columns,
            mapels: [],
            guruOptions: [],
            modalShow: false,
            notifMsg: null,
            modalTitle: null,
            form: {
                name: null,
                description: null,
                kkm: null,
                jenjang: null,
                gurus: []
            }
        }
    },
    mounted() {
        this.readData()
        this.readDataGuru()
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
                .get(vm.url('mapel/read'), { params: params })
                .then((response) => {
                    vm.mapels = response.data.models
                    vm.loading = false
                })
                .catch((error) => vm.$onAjaxError(error))
        },
        readDataGuru() {
            const vm = this
            const params = {
                req: 'all'
            }
            vm.axios
                .get(vm.url('guru/read'), { params: params })
                .then((response) => {
                    vm.guruOptions = response.data.models
                })
                .catch((error) => vm.$$onAjaxError(error))
        },
        newData() {
            const vm = this
            vm.form = {}
            vm.form.kkm = 80
            vm.modalTitle = 'Tambah Mata Pelajaran'
            vm.notifMsg = 'Berhasil Menambahkan Mata Pelajaran'
            vm.modalShow = true
        },
        writeData() {
            const vm = this
            const params = {
                req: 'write',
                ...vm.form
            }

            vm.axios
                .post(vm.url('mapel/write'), params)
                .then((response) => {
                    console.log(response)
                    vm.openNotification(vm.notifMsg, 'success')
                    vm.readData()
                    vm.modalShow = false
                })
                .catch((e) => (vm.validation = vm.$onAjaxError(e)))
        },
        readDataSingle(_id) {
            const vm = this
            const params = {
                req: 'single',
                id: _id
            }
            vm.axios
                .get(vm.url('mapel/read'), { params: params })
                .then((response) => {
                    vm.form = response.data.models
                    response.data.models.gurus
                        ? (vm.form.gurus = JSON.parse(
                              response.data.models.gurus
                          ))
                        : (vm.form.gurus = [])
                    vm.modalTitle = 'Edit Mata Pelajaran'
                    vm.notifMsg = 'Berhasil Mengupdate Mata Pelajaran'
                    vm.modalShow = true
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        deleteData(_id) {
            const vm = this
            const params = {
                req: 'delete',
                id: _id
            }
            console.log(params)
            vm.axios
                .get(vm.url('mapel/write'), params)
                .then(() => {
                    vm.openNotification(
                        'Mata Pelajaran Berhasil Dihapus',
                        'success'
                    )
                    vm.readData()
                })
                .catch((e) => vm.$onAjaxError(e))
        }
    }
}
</script>
