<template>
    <h1 style="margin-left: 30px">Jurnal Kelas</h1>

    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :columns="jurnalKelasTableColumn"
                title="Jurnal Kelas"
                style="width: 100%"
            >
                <a-button
                    @click="jurnalKelasModalAddvisible = true"
                    type="primary"
                    style="margin-bottom: 20px"
                    >Tambah
                </a-button>
                <a-modal
                    v-model:visible="jurnalKelasModalAddvisible"
                    title="Tambah Jurnal Kelas"
                    @ok="handleOk"
                >
                    <template #footer> </template>
                    <a-form
                        :layout="'vertical'"
                        :model="jurnalKelasAddForm"
                        @finish="onFinishJurnalForm"
                        @finishFailed="onFinishFailedJurnalForm"
                        ref="jurnalKelasAddForm"
                    >
                        <a-form-item
                            label="Kelas"
                            name="id_kelas"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Kelas tidak boleh kosong'
                                }
                            ]"
                        >
                            <a-select
                                v-model:value="jurnalKelasAddForm.id_kelas"
                                placeholder="Pilih kelas"
                                @change="handleChange"
                            >
                                <a-select-option
                                    v-for="(kelas, index) in classes"
                                    :key="kelas.id"
                                    :value="kelas.id"
                                    >{{
                                        `${kelas.jenjang}  ${kelas.section}`
                                    }}</a-select-option
                                >
                            </a-select>
                        </a-form-item>
                        <a-form-item
                            label="Catatan"
                            name="catatan"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Catatan tidak boleh kosong'
                                }
                            ]"
                        >
                            <a-textarea
                                v-model:value="jurnalKelasAddForm.catatan"
                                :rows="4"
                            />
                        </a-form-item>

                        <a-form-item>
                            <a-button
                                html-type="submit"
                                type="primary"
                                :loading="loading"
                                >Simpan
                            </a-button>
                        </a-form-item>
                    </a-form>
                </a-modal>
                <a-table
                    :columns="jurnalKelasTableColumn"
                    :data-source="jurnalKelasDataSources"
                    :loading="jurnalKelasDataSourcesLoading"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'aksi'">
                            <a-space display="flex">
                                <a-button
                                    type="danger"
                                    @click="alert(record.id, deleteJurnal)"
                                    >Hapus</a-button
                                >
                                <a-button
                                    @click="showJurnalUpdateModal(record.id)"
                                    type="primary"
                                    >Edit</a-button
                                >
                                <a-modal
                                    v-model:visible="
                                        jurnalKelasModalUpdateVisible
                                    "
                                    title="Edit Jurnal Kelas"
                                    @ok="handleOk"
                                >
                                    <template #footer> </template>
                                    <a-form
                                        :layout="'vertical'"
                                        :model="jurnalKelasUpdateForm"
                                        @finish="onFinishJurnalUpdateForm"
                                        @finishFailed="
                                            onFinishFailedJurnalUpdateForm
                                        "
                                        ref="jurnalKelasUpdateForm"
                                    >
                                        <a-form-item
                                            label="Kelas"
                                            name="id_kelas"
                                            :rules="[
                                                {
                                                    required: true,
                                                    message:
                                                        'Kelas tidak boleh kosong'
                                                }
                                            ]"
                                        >
                                            <a-select
                                                v-model:value="
                                                    jurnalKelasUpdateForm.id_kelas
                                                "
                                                placeholder="Pilih kelas"
                                                @change="handleChange"
                                            >
                                                <a-select-option
                                                    v-for="(
                                                        kelas, index
                                                    ) in classes"
                                                    :key="kelas.id"
                                                    :value="kelas.id"
                                                    >{{
                                                        `${kelas.jenjang}  ${kelas.section}`
                                                    }}</a-select-option
                                                >
                                            </a-select>
                                        </a-form-item>
                                        <a-form-item
                                            label="Catatan"
                                            name="catatan"
                                            :rules="[
                                                {
                                                    required: true,
                                                    message:
                                                        'Catatan tidak boleh kosong'
                                                }
                                            ]"
                                        >
                                            <a-textarea
                                                v-model:value="
                                                    jurnalKelasUpdateForm.catatan
                                                "
                                                :rows="4"
                                            />
                                        </a-form-item>

                                        <a-form-item>
                                            <a-button
                                                html-type="submit"
                                                type="primary"
                                                :loading="loading"
                                                >Simpan
                                            </a-button>
                                        </a-form-item>
                                    </a-form>
                                </a-modal>
                            </a-space>
                        </template>
                    </template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>
</template>

<script>
import moment from 'moment'
import 'moment/locale/id'

const jurnalKelasTableColumn = [
    {
        title: 'No',
        dataIndex: 'no',
        key: 'no'
    },
    {
        title: 'Tanggal',
        dataIndex: 'tanggal',
        key: 'tanggal'
    },
    {
        title: 'Kelas',
        dataIndex: 'kelas',
        key: 'kelas'
    },
    {
        title: 'Catatan',
        dataIndex: 'catatan',
        key: 'catatan'
    },
    {
        title: 'Aksi',
        dataIndex: 'aksi',
        key: 'aksi'
    }
]

export default {
    data() {
        return {
            jurnalKelasTableColumn,
            jurnalKelasDataSources: [],
            jurnalKelasDataSourcesLoading: false,
            jurnalKelasModalAddvisible: false,
            jurnalKelasAddForm: {
                id_kelas: null,
                catatan: null
            },
            jurnalKelasModalUpdateVisible: false,
            jurnalKelasUpdateForm: {
                id: null,
                id_kelas: null,
                id_guru: null,
                catatan: null
            },
            classes: []
        }
    },
    mounted() {
        this.getKelas()
        this.getJurnalKelas()
    },
    methods: {
        getKelas() {
            this.axios
                .get(this.url('kelas/read'), { params: { req: 'table' } })
                .then((response) => {
                    this.classes = response.data.models
                })
                .catch((e) => this.$onAjaxError(e))
        },

        getJurnalKelas() {
            this.jurnalKelasDataSourcesLoading = true
            this.jurnalKelasDataSources = []
            this.axios
                .get(this.url('user'))
                .then((response) => {
                    this.axios
                        .get(this.url('jurnal-kelas'), {
                            params: { id_guru: response.data.id }
                        })
                        .then((response) => {
                            response.data.data.forEach((item, index) => {
                                this.jurnalKelasDataSources.push({
                                    id: item.id,
                                    no: index + 1,
                                    tanggal: moment(item.created_at).format(
                                        'dddd, DD MMMM YYYY'
                                    ),
                                    kelas: `${item.kelas.jenjang} - ${item.kelas.section}`,
                                    catatan: item.catatan
                                })
                            })
                            this.jurnalKelasDataSourcesLoading = false
                        })
                        .catch((e) => this.$onAjaxError(e))
                })
                .catch((e) => this.$onAjaxError(e))
        },
        handleChange(value) {
            this.jurnalKelasAddForm.id_kelas = value
        },
        onFinishJurnalForm() {
            this.axios
                .get(this.url('user'))
                .then((response) => {
                    this.jurnalKelasAddForm = {
                        ...this.jurnalKelasAddForm,
                        id_guru: response.data.id
                    }
                    this.axios
                        .post(this.url('jurnal-kelas'), this.jurnalKelasAddForm)
                        .then((response) => {
                            this.jurnalKelasModalAddvisible = false
                            this.jurnalKelasAddForm = {
                                id_kelas: null,
                                catatan: null,
                                id_guru: null
                            }
                            this.getJurnalKelas()
                            this.$notification.success({
                                message: 'Berhasil',
                                description: response.data.message
                            })
                        })
                        .catch((e) => {
                            this.$notification.error({
                                message: 'Kesalahan',
                                description: e.response.data.message
                            })
                        })
                })
                .catch((e) => this.$onAjaxError(e))
        },
        deleteJurnal(id) {
            this.axios
                .delete(this.url('jurnal-kelas/' + id))
                .then((response) => {
                    this.getJurnalKelas()
                    this.$notification.success({
                        message: 'Berhasil',
                        description: response.data.message
                    })
                })
                .catch((e) => {
                    this.$notification.error({
                        message: 'Kesalahan',
                        description: e.response.data.message
                    })
                })
        },
        showJurnalUpdateModal(id) {
            this.axios
                .get(this.url('jurnal-kelas/' + id))
                .then((response) => {
                    this.jurnalKelasUpdateForm = {
                        id: response.data.data.id,
                        id_kelas: response.data.data.id_kelas,
                        id_guru: response.data.data.id_guru,
                        catatan: response.data.data.catatan
                    }
                    this.jurnalKelasModalUpdateVisible = true
                })
                .catch((e) => this.$onAjaxError(e))
        },
        onFinishJurnalUpdateForm() {
            this.jurnalKelasModalUpdateVisible = false
            this.axios
                .get(this.url('user'))
                .then((response) => {
                    this.axios
                        .put(
                            this.url(
                                'jurnal-kelas/' + this.jurnalKelasUpdateForm.id
                            ),
                            this.jurnalKelasUpdateForm
                        )
                        .then((response) => {
                            this.jurnalKelasUpdateForm = {
                                id_kelas: null,
                                id_guru: null,
                                catatan: null
                            }
                            this.getJurnalKelas()
                            this.$notification.success({
                                message: 'Berhasil',
                                description: response.data.message
                            })
                        })
                        .catch((e) => {
                            this.$notification.error({
                                message: 'Kesalahan',
                                description: e.response.data.message
                            })
                        })
                })
                .catch((e) => this.$onAjaxError(e))
        }
    }
}
</script>
