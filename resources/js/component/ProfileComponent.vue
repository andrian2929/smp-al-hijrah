<template>
   
        <a-row type="flex" justify="center" style="margin: 0" :gutter="[16, 16]">
            <a-skeleton :loading="loading" :avatar="true">
        <a-col :xs="10" :lg="6" style="margin-bottom: 24px">
        
            <img
                style="width: 80%"
                :src="form.image == 'default.png' ? '/img/no_profile.png' : '/img/profile_photo/' + form.image"
                alt="profile picture"
            />
    
        </a-col>
        <a-col :xs="13" :lg="17">
            <h1>{{ models.name }}</h1>
            <a-button class="btn btn-dark"
                ><span
                    v-if="
                        models.roles && models.roles[0].display_name == 'siswa'
                    "
                    >NIS:</span
                ><span v-else>NIP</span>&nbsp;
                {{ `  ${models.no_induk}` }}</a-button
            >
            <h5 style="padding-top: 8px">
                {{ models.roles && models.roles[0].name }}
            </h5>
            <a-button
                :class="`btn ${models.is_active ? 'btn-green' : 'btn-grey'}`"
            >
                {{ models.is_active ? 'aktif' : 'nonaktif' }}
            </a-button>
        </a-col>
    </a-skeleton>
    </a-row>

    <a-row type="flex" justify="center" style="margin: 0">
        <a-col :span="23">
            <a-card
                style="width: 100%"
                :tab-list="[
                    { key: 'profile', tab: 'Profile' },
                    { key: 'pengaturan', tab: 'Pengaturan' }
                ]"
                :active-tab-key="tab"
                @tabChange="(key) => (tab = key)"
            >
                <div v-if="tab === 'profile'">
                    <a-row type="flex" style="margin: 0" :gutter="[16, 16]">
                        <!-- Biografi Card -->
                        <a-col :xs="24" :lg="12">
                            <a-card title="Biografi" style="width: 100%">
                                <template #extra
                                    ><a
                                        @click="
                                            () => {
                                                modal.biografi = true
                                                editPart = 'biografi'
                                            }
                                        "
                                        >Edit</a
                                    ></template
                                >
                                <a-row :gutter="[8, 8]">
                                    <a-col :span="8">
                                        <strong>Nama Lengkap</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.name }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Tempat Lahir</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.tempat_lahir }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Tanggal lahir</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.tanggal_lahir }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Jenis Kelamin</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.jenis_kelamin.charAt(0).toUpperCase() + models.jenis_kelamin.slice(1) }}
                                    </a-col>
                                </a-row>
                            </a-card>
                        </a-col>
                        <!-- Ciri Fisik Card -->
                        <a-col :xs="24" :lg="12">
                            <a-card title="Ciri Fisik" style="width: 100%">
                                <template #extra
                                    ><a
                                        @click="
                                            () => {
                                                modal.ciriFisik = true
                                                editPart = 'ciri_fisik'
                                            }
                                        "
                                        >Edit</a
                                    ></template
                                >
                                <a-row :gutter="[8, 8]">
                                    <a-col :span="8">
                                        <strong>Tinggi</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.tinggi }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Berat</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.berat }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Golongan Darah</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.gol_darah }}
                                    </a-col>
                                </a-row>
                            </a-card>
                        </a-col>
                        <!-- alamat card -->
                        <a-col :xs="24" :lg="12">
                            <a-card title="Alamat" style="width: 100%">
                                <template #extra
                                    ><a
                                        @click="
                                            () => {
                                                modal.alamat = true
                                                editPart = 'alamat'
                                            }
                                        "
                                        >Edit</a
                                    ></template
                                >
                                <a-row :gutter="[8, 8]">
                                    <a-col :span="8">
                                        <strong>Jalan</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.alamat }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Desa/Kelurahan</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.kelurahan }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Kecamatan</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.kecamatan }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Kabupaten/Kota</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.kota }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Provinsi</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.provinsi }}
                                    </a-col>
                                </a-row>
                            </a-card>
                        </a-col>
                        <!-- Contact card -->
                        <a-col :xs="24" :lg="12">
                            <a-card title="Kontak" style="width: 100%">
                                <template #extra
                                    ><a
                                        @click="
                                            () => {
                                                modal.contact = true
                                                editPart = 'contact'
                                            }
                                        "
                                        >Edit</a
                                    ></template
                                >
                                <a-row :gutter="[8, 8]">
                                    <a-col :span="8">
                                        <strong>Nomor Telepon</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.no_telp }}
                                    </a-col>
                                    <a-col :span="8">
                                        <strong>Email</strong>
                                    </a-col>
                                    <a-col :span="16">
                                        {{ models.email }}
                                    </a-col>
                                </a-row>
                            </a-card>
                        </a-col>
                        <!-- Orangtua Card -->
                        <a-col :xs="24">
                            <a-card title="Wali" style="width: 100%">
                                <template #extra
                                    ><a
                                        @click="
                                            () => {
                                                modal.orangtua = true
                                                editPart = 'orangtua'
                                            }
                                        "
                                        >Edit</a
                                    ></template
                                >
                                <div style="overflow-x: auto">
                                    <a-table
                                        :columns="[
                                            { title: '', dataIndex: 'type' },
                                            {
                                                title: 'Nama',
                                                dataIndex: 'name'
                                            },
                                            {
                                                title: 'Status',
                                                dataIndex: 'status'
                                            },
                                            {
                                                title: 'tanggal Lahir',
                                                dataIndex: 'tanggal_lahir'
                                            },
                                            {
                                                title: 'Pekerjaan',
                                                dataIndex: 'pekerjaan'
                                            },
                                            {
                                                title: 'Keterangan Lainnya',
                                                dataIndex: 'keterangan'
                                            }
                                        ]"
                                        :pagination="false"
                                        :data-source="models.orangtua"
                                    />
                                </div>
                            </a-card>
                        </a-col>
                    </a-row>
                </div>
                <div v-else-if="tab === 'pengaturan'">
                    <a-row type="flex" style="margin: 0" :gutter="[16, 16]">
                        <a-col :xs="24" :lg="12">
                            <h5>Pengaturan Password</h5>
                            Password Sekarang
                            <a-input-password
                                v-model:value="form.password"
                                placeholder=""
                                style="margin-top: 10px; margin-bottom: 15px"
                            />
                            Password Baru
                            <a-input-password
                                v-model:value="form.new_password"
                                placeholder=""
                                style="margin-top: 10px"
                            />
                            <a-button
                                :class="`btn-green`"
                                style="margin-top: 15px"
                                @click="changePassword"
                            >
                                <save-outlined /> Simpan
                            </a-button>
                        </a-col>
                        <a-col :xs="24" :lg="12">
                            <h5>Gambar Profil</h5>
                            Pilih gambar (.jpg/.png)
                            <br />
                            <br />
                            <a-upload
                            v-model = "imageNew"
                                name="file"
                                multiple="false"
                                :before-upload="beforeUpload"
                                @remove="handleRemove"

                            >
                                <a-button>
                                    <upload-outlined></upload-outlined>
                                    Click to Upload
                                </a-button>
                            </a-upload>
                            <a-button
                                :class="`btn-green`"
                                style="margin-top: 15px"
                                @click="uploadImage"
                            >
                                <save-outlined /> Simpan
                            </a-button>
                        </a-col>
                    </a-row>
                </div>
            </a-card>
        </a-col>
    </a-row>
    <!-- Modal Biografi -->
    <a-modal
        v-if="editMode"
        v-model:visible="modal.biografi"
        title="Edit Biografi"
        @ok="writeData"
    >
        <a-form
            name="biografi"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 16 }"
            class="login-form"
            autocomplete="off"
        >
            <a-form-item
                label="Nama Lengkap"
                name="name"
                :class="{ 'ant-form-item-has-error': validation.name }"
            >
                <a-input v-model:value="form.name" />
            </a-form-item>

            <a-form-item
                label="Tempat Lahir"
                name="tempat_lahir"
                :class="{ 'ant-form-item-has-error': validation.tempat_lahir }"
            >
                <a-input v-model:value="form.tempat_lahir" />
            </a-form-item>

            <a-form-item
                label="Tanggal Lahir"
                name="tanggal_lahir"
                :class="{ 'ant-form-item-has-error': validation.tanggal_lahir }"
            >
                <a-date-picker
                    v-model:value="form['tanggal_lahir']"
                    value-format="YYYY-MM-DD"
                />
            </a-form-item>

            <a-form-item
                label="Jenis Kelamin"
                name="jenis_kelamin"
                :class="{ 'ant-form-item-has-error': validation.jenis_kelamin }"
            >
                <a-select
                    v-model:value="form.jenis_kelamin"
                    style="width: 100%"
                >
                    <a-select-option value="laki-laki"
                        >Laki-laki</a-select-option
                    >
                    <a-select-option value="perempuan"
                        >Perempuan</a-select-option
                    >
                </a-select>
            </a-form-item>
        </a-form>
    </a-modal>
    <!-- Modal CiriFisik -->
    <a-modal
        v-if="editMode"
        v-model:visible="modal.ciriFisik"
        title="Edit Ciri Fisik"
        @ok="writeData"
    >
        <a-form
            name="ciri_fisik"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 16 }"
            class="login-form"
            autocomplete="off"
        >
            <a-form-item
                label="Tinggi"
                name="tinggi"
                :class="{ 'ant-form-item-has-error': validation.tinggi }"
            >
                <a-input v-model:value="form.tinggi" suffix="cm" />
            </a-form-item>

            <a-form-item
                label="Berat"
                name="berat"
                :class="{ 'ant-form-item-has-error': validation.berat }"
            >
                <a-input v-model:value="form.berat" suffix="kg" />
            </a-form-item>

            <a-form-item
                label="Golongan Darah"
                name="gol_darah"
                :class="{ 'ant-form-item-has-error': validation.gol_darah }"
            >
                <a-select v-model:value="form.gol_darah" style="width: 100%">
                    <a-select-option value="A">A</a-select-option>
                    <a-select-option value="B">B</a-select-option>
                    <a-select-option value="AB">AB</a-select-option>
                    <a-select-option value="O">O</a-select-option>
                </a-select>
            </a-form-item>
        </a-form>
    </a-modal>
    <!-- Modal alamat -->
    <a-modal
        v-if="editMode"
        v-model:visible="modal.alamat"
        title="Edit Alamat"
        @ok="writeData"
    >
        <a-form
            name="alamat"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 16 }"
            class="login-form"
            autocomplete="off"
        >
            <a-form-item
                label="Jalan"
                name="alamat"
                :class="{ 'ant-form-item-has-error': validation.alamat }"
            >
                <a-input v-model:value="form.alamat" />
            </a-form-item>

            <a-form-item
                label="Desa/Kelurahan"
                name="kelurahan"
                :class="{ 'ant-form-item-has-error': validation.kelurahan }"
            >
                <a-input v-model:value="form.kelurahan" />
            </a-form-item>

            <a-form-item
                label="Kecamatan"
                name="kecamatan"
                :class="{ 'ant-form-item-has-error': validation.kecamatan }"
            >
                <a-input v-model:value="form.kecamatan" />
            </a-form-item>

            <a-form-item
                label="Kabupaten/Kota"
                name="kota"
                :class="{ 'ant-form-item-has-error': validation.kota }"
            >
                <a-input v-model:value="form.kota" />
            </a-form-item>

            <a-form-item
                label="Provinsi"
                name="provinsi"
                :class="{ 'ant-form-item-has-error': validation.provinsi }"
            >
                <a-input v-model:value="form.provinsi" />
            </a-form-item>
        </a-form>
    </a-modal>
    <!-- Modal Contact -->
    <a-modal
        v-if="editMode"
        v-model:visible="modal.contact"
        title="Edit Kontak"
        @ok="writeData"
    >
        <a-form
            name="contact"
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
    <!-- Modal Orangtua -->
    <a-modal
        v-if="editMode"
        v-model:visible="modal.orangtua"
        title="Edit Wali"
        @ok="writeData"
    >
        <a-form
            name="contact"
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
    <!-- <a-modal
        v-if="editMode"
        v-model:visible="modal.orangtua"
        title="Edit Wali"
    >
        <a-form
            name="orangtua"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 16 }"
            class="login-form"
            autocomplete="off"
        >
            <template v-for="form_ortu in form.orangtua" :key="form_ortu.id">
                <a-form-item label="" name="type">
                    <a-select
                        disabled
                        v-model:value="form_ortu.type"
                        style="width: 150%"
                    >
                        <a-select-option value="Ayah">Ayah</a-select-option>
                        <a-select-option value="Ibu">Ibu</a-select-option>
                        <a-select-option value="Wali">Wali</a-select-option>
                    </a-select>
                </a-form-item>

                <a-form-item
                    label="Status"
                    name="status"
                    :class="{
                        'ant-form-item-has-error': validation.orangtua
                    }"
                >
                    <a-radio-group v-model:value="form_ortu.status">
                        <a-radio value="kandung">Kandung</a-radio>
                        <a-radio value="angkat">Angkat</a-radio>
                    </a-radio-group>
                </a-form-item>

                <a-form-item
                    label="Nama Lengkap"
                    name="name"
                    :class="{
                        'ant-form-item-has-error': validation.orangtua
                    }"
                >
                    <a-input v-model:value="form_ortu.name" />
                </a-form-item>

                <a-form-item
                    label="Tanggal Lahir"
                    name="tanggal_lahir"
                    :class="{
                        'ant-form-item-has-error': validation.orangtua
                    }"
                >
                    <a-date-picker
                        v-model:value="form['tanggal_lahir']"
                        value-format="YYYY-MM-DD"
                    />
                </a-form-item>

                <a-form-item
                    label="Pekerjaan"
                    name="pekerjaan"
                    :class="{
                        'ant-form-item-has-error': validation.orangtua
                    }"
                >
                    <a-input v-model:value="form_ortu.pekerjaan" />
                </a-form-item>

                <a-form-item
                    label="Keterangan Lainnya"
                    name="keterangan"
                    :class="{
                        'ant-form-item-has-error': validation.orangtua
                    }"
                >
                    <a-textarea v-model:value="form_ortu.keterangan" />
                </a-form-item>
            </template>
        </a-form>
    </a-modal> -->
</template>

<script>
export default {
    props: {
        userId: Number,
        editMode: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            tab: 'profile',
            models: {},
            form: {},
            editPart: null,
            modal: {
                biografi: false,
                ciriFisik: false,
                alamat: false,
                contact: false,
                orangtua: false
            },
            imageNew: null,
            loading : true
        }
    },
    mounted() {
        this.readSingleData(this.$props.userId)
    },
    methods: {
        readSingleData(_id) {
            const vm = this
            vm.loading = true
            const params = {
                req: 'single_full',
                id: _id
            }
            vm.axios
                // .get(vm.url('user/read'), { params: params })
                // .then((response) => {
                //     vm.models = response.data.models
                //     vm.form = response.data.models
                //     this.image = response.data.models.image
                //     vm.loading = false
                // })
                .catch((error) => {
                    vm.$onAjaxError(error)
                })
        },
        changePassword() {
            this.editPart = 'auth'
            this.writeData()
        },
        writeData() {
            const vm = this
            const params = {
                req: 'edit',
                editPart: vm.editPart,
                ...vm.form
            }
            vm.axios
                // .post(vm.url('user/write'), params)
                .then(() => {
                    vm.openNotification('Berhasil mengupdate data', 'success')
                    vm.modal = {
                        biografi: false,
                        ciriFisik: false,
                        alamat: false,
                        contact: false,
                        orangtua: false
                    }
                    vm.readSingleData(this.$props.userId)
                })
                .catch((e) => (vm.validation = vm.$onAjaxError(e)))
        },
        beforeUpload(file) {
           this.imageNew = file
            return false
        },
        handleRemove(file) {
            this.imageNew = null
           
        },
        uploadImage(){
          
          this.editPart = 'upload_profile';
          let formData = new FormData();
            formData.append('image', this.imageNew);
            formData.append('id', this.$props.userId);
            formData.append('editPart', this.editPart);
            formData.append('name', this.form.name);
            formData.append('oldImage', this.form.image);
            formData.append('req', 'edit');
     
            this.axios
                .post(this.url('user/write'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    console.log(response)
                    this.readSingleData(this.$props.userId)
                
                  this.$notification.success({
                        message: 'Berhasil',
                        description: 'Berhasil mengganti foto profil'
                    })

                    this.$router.go()
                   
                })
                .catch((e) => {
                 
                    this.$notification.error({
                        message: e.response.data.message,
                        description: JSON.stringify(e.response.data.errors)
                    })
                })
        
        }

    }
}
</script>
<style scoped>
table {
    width: 100%;
}
td {
    text-align: right;
}
.text-right {
    text-align: right;
}
.btn {
    padding: 1px 6px;
    border-radius: 4px;
    pointer-events: none;
    cursor: default;
}
.btn-dark {
    background-color: black;
    color: white;
}
.btn-green {
    background-color: green;
    color: white;
}
.btn-grey {
    background-color: grey;
    color: white;
}
</style>
