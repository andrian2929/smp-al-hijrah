<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card title="Tambah Akun">
                <a-card title="Tambah Manual">
                    <p>Pilih Jenis Akun</p>
                    <a-select v-model:value="role" style="width: 200px">
                        <a-select-option
                            v-for="rl in roleOptions"
                            :key="rl.id"
                            :value="rl.display_name"
                        >
                            {{ rl.name }}
                        </a-select-option>
                    </a-select>
                    <a-button type="primary" @click="newUser"
                        >Buat Akun</a-button
                    >
                </a-card>
                <a-card title="Input masal" :style="{ marginTop: '16px' }">
                    <p>
                        <a-alert
                            message="Fitur input hanya berlaku untuk input siswa"
                            type="warning"
                        />
                    </p>
                    <p>
                        Sebelum melakukan input masal, silakan unduh template
                        Excel
                        <a style="color: #189dff" @click="downloadTemplate"
                            >disini</a
                        >
                        dan cek kode kelas
                        <a style="color: #189dff" @click="showKelasModal = true"
                            >disini</a
                        >
                    </p>
                    <a-upload
                        v-model:file-list="fileList"
                        name="file"
                        :file-list="fileList"
                        :multiple="false"
                        :beforeUpload="beforeUpload"
                        :customRequest="prosesInputMasal"
                    >
                        <a-button>
                            <upload-outlined></upload-outlined>
                            Unggah berkas
                        </a-button>
                    </a-upload>
                    <a-modal
                        title="Kode Kelas"
                        v-model:visible="showKelasModal"
                        @ok="handleOk"
                    >
                        <a-table
                            :columns="kelasColumns"
                            :data-source="kelas"
                            :pagination="false"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key == 'kode'">
                                    <span>{{ record.id }}</span>
                                </template>
                                <template v-else-if="column.key == 'kelas'">
                                    <span>
                                        {{ record.jenjang }} -
                                        {{ record.section }}
                                    </span>
                                </template>
                            </template>
                        </a-table>
                    </a-modal>
                </a-card>
            </a-card>
        </a-col>
    </a-row>
</template>

<script>
import FileSaver from 'file-saver'
import * as XLSX from 'xlsx'
const kelasColumns = [
    {
        title: 'Kode',
        dataIndex: 'id',
        key: 'kode'
    },
    {
        title: 'Kelas',
        dataIndex: 'kelas',
        key: 'kelas'
    }
]

export default {
    data() {
        return {
            role: 'Siswa',
            roleOptions: [],
            showKelasModal: false,
            kelasColumns,
            kelas: [],
            fileList: []
        }
    },
    mounted() {
        this.getAllRoles()
        this.getKelas()
    },
    methods: {
        newUser() {
            this.$router.push({
                path: `/admin/kelola_akun/new/${this.role.toLowerCase()}`
            })
        },
        getAllRoles() {
            const vm = this
            const params = { req: 'roles' }
            vm.axios
                .get(vm.url('user/read'), { params: params })
                .then((response) => {
                    vm.roleOptions = response.data.models
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        handleOk() {
            this.showKelasModal = false
        },
        getKelas() {
            const vm = this
            const params = { req: 'all' }
            vm.axios
                .get(vm.url('kelas/read'), { params: params })
                .then((response) => {
                    vm.kelas = response.data.models
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        downloadTemplate() {
            this.axios
                .get(this.url('siswa/input-masal/template'), {
                    responseType: 'blob'
                })
                .then((response) => {
                    FileSaver.saveAs(
                        new Blob([response.data]),
                        'template_input_masal.xlsx'
                    )
                })
        },
        beforeUpload(file) {
            if (
                file.type !=
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ) {
                this.$message.error(
                    'File yang diunggah harus berupa file Excel'
                )
                return false
            }
        },
        prosesInputMasal(file) {
            const vm = this
            const formData = new FormData()
            formData.append('file', vm.fileList[0].originFileObj)
            vm.axios
                .post(vm.url('siswa/input-masal'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    responseType: 'blob'
                })
                .then((response) => {
                    const fileName =
                        response.headers['content-disposition'].split(
                            'filename='
                        )[1]
                    console.log(fileName)
                    FileSaver.saveAs(
                        new Blob([response.data], {
                            type: 'application/pdf'
                        }),
                        fileName.substring(1, fileName.length - 1)
                    )
                })
                .catch((e) => {
                    $this.notification.error({
                        message: 'Terjadi kesalahan',
                        description: e.response.data.message
                    })
                })
        }
    }
}
</script>
