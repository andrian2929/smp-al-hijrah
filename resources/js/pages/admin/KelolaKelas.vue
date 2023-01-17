<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="TATA KELOLA KELAS"
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
                    <a-select
                        v-model:value="filter.class"
                        style="width: 120px"
                        placeholder="Pilih kelas"
                        @change="handleKelasChange"
                    >
                        <a-select-option value="VII">VII</a-select-option>
                        <a-select-option value="VIII">VIII</a-select-option>
                        <a-select-option value="IX">IX</a-select-option>
                    </a-select>
                    <a-button @click="newData">Tambah</a-button>
                </a-space>
                <a-row type="flex" justify="between" :gutter="[8, 8]">
                    <a-col
                        v-for="cls in classes"
                        :key="cls.id"
                        :xs="24"
                        :sm="12"
                        :md="8"
                        :xl="6"
                    >
                        <a-card :title="`${cls.jenjang} - ${cls.section}`">
                            <template #extra>
                                <a @click="findKelas(cls.id)">Edit </a>
                            </template>
                            Wali Kelas :
                            {{ cls.wali_kelas ? cls.wali_kelas.name : '-'
                            }}<br />
                            {{ cls.siswa_count }}&nbsp;Siswa<br />
                            <router-link :to="`/admin/kelola_kelas/${cls.id}`"
                                ><a-button type="primary" style="float: right"
                                    >Detail</a-button
                                ></router-link
                            >
                        </a-card>
                    </a-col>
                </a-row>
            </a-card>
        </a-col>
    </a-row>
    <a-modal v-model:visible="modalAdd" title="Tambah Kelas" @ok="writeData">
        <a-form
            :label-col="{ span: 6 }"
            :wrapper-col="{ span: 18 }"
            autocomplete="off"
        >
            <a-form-item label="Jenjang" data-column="jenjang" required>
                <a-select v-model:value="form.jenjang" style="width: 100%">
                    <a-select-option value="VII">VII</a-select-option>
                    <a-select-option value="VIII">VIII</a-select-option>
                    <a-select-option value="IX">IX</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item
                label="Wali Kelas"
                data-column="wali_kelas_id"
                required
            >
                <a-select
                    v-model:value="form.wali_kelas_id"
                    style="width: 100%"
                >
                    <a-select-option
                        v-for="guru in gurus"
                        :key="guru.id"
                        :label="guru.name"
                        :value="guru.id"
                        >{{ guru.name }}</a-select-option
                    >
                </a-select>
            </a-form-item>
        </a-form>
    </a-modal>
    <a-modal v-model:visible="modalEdit" title="Edit Wali Kelas" @ok="editData">
        <a-form
            :label-col="{ span: 6 }"
            :wrapper-col="{ span: 18 }"
            autocomplete="off"
        >
            <a-form-item label="Kelas" data-column="kelas" required>
                <a-input disabled :value="form.className" />
            </a-form-item>
            <a-form-item
                label="Wali Kelas"
                data-column="wali_kelas_id"
                required
            >
                <a-select
                    v-model:value="form.wali_kelas_id"
                    style="width: 100%"
                >
                    <a-select-option
                        v-for="guru in gurus"
                        :key="guru.id"
                        :label="guru.name"
                        :value="guru.id"
                        >{{ guru.name }}</a-select-option
                    >
                </a-select>
            </a-form-item>
        </a-form>
    </a-modal>
</template>

<script>
export default {
    data() {
        return {
            classes: [],
            filter: {
                class: null
            },
            form: {
                jenjang: 'VII',
                wali_kelas_id: null
            },
            modalAdd: false,
            modalEdit: false,
            gurus: []
        }
    },
    mounted() {
        this.readData()
        this.getAllGuru()
    },
    methods: {
        readData() {
            const vm = this
            const params = {
                req: 'table',
                ...vm.filter
            }

            vm.axios
                .get(vm.url('kelas/read'), { params: params })
                .then((response) => {
                    vm.classes = response.data.models
                })
                .catch((error) => vm.$onAjaxError(error))
        },
        findKelas(_id) {
            const vm = this
            const kelas = vm.classes.find((obj) => obj.id === _id)
            vm.form.id = kelas.id
            vm.form.wali_kelas_id = kelas.wali_kelas
                ? kelas.wali_kelas.id
                : null
            vm.form.className = `${kelas.jenjang} - ${kelas.section}`
            vm.modalEdit = true
        },
        newData() {
            const vm = this
            vm.form = {}
            vm.modalAdd = true
        },
        getAllGuru() {
            const vm = this
            const params = {
                req: 'get_all_guru'
            }
            vm.axios
                .get(vm.url('kelas/read'), { params: params })
                .then((response) => {
                    vm.gurus = response.data.models
                    console.log(response.data)
                })
                .catch((error) => vm.$onAjaxError(error))
        },
        writeData() {
            const vm = this
            const params = {
                req: 'add',
                ...vm.form
            }
            vm.axios
                .post(vm.url('kelas/write'), params)
                .then(() => {
                    vm.openNotification('Sukses Menambah kelas', 'success')
                    vm.readData()
                    vm.modalAdd = false
                })
                .catch((e) => (vm.validation = vm.$onAjaxError(e)))
        },
        editData() {
            const vm = this
            const params = {
                req: 'edit',
                ...vm.form
            }
            vm.axios
                .post(vm.url('kelas/write'), params)
                .then(() => {
                    vm.openNotification('Sukses Mengupdate kelas', 'success')
                    vm.readData()
                    vm.modalEdit = false
                })
                .catch((e) => (vm.validation = vm.$onAjaxError(e)))
        },
        handleKelasChange(value) {
            this.filter.class = value
            console.log(this.filter.class)
            this.readData()
        }
    }
}
</script>
