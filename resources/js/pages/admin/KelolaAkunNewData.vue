<template>
    <div style="margin: auto 20px">
        <a-card
            :title="`Tambah Data ${
                role.charAt(0).toUpperCase() + role.slice(1)
            }`"
        >
            <a-form
                :layout="'vertical'"
                :model="formGeneral"
                @finish="onFinish"
                @finishFailed="onFinishFailed"
                ref="formGeneral"
            >
                <a-row gutter="{16}">
                    <a-col span="12">
                        <a-card title="Data Pengguna Baru" :bordered="false">
                            <a-form-item
                                label="Username"
                                name="username"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Masukkan username anda'
                                    },
                                    {
                                        min: 5,
                                        message: 'Username minimal 5 karakter'
                                    },
                                    {
                                        max: 20,
                                        message: 'Username maksimal 20 karakter'
                                    },
                                    {
                                        pattern: /^[^\s]*$/,
                                        message: 'Username tidak boleh spasi'
                                    }
                                ]"
                            >
                                <a-input
                                    v-model:value="formGeneral.username"
                                    placeholder="Masukkan username"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Email"
                                name="email"
                                :rules="[
                                    {
                                        type: 'email',
                                        message:
                                            'Email yang anda masukkan tidak valid'
                                    },
                                    {
                                        required: true,
                                        message: 'Masukkan email anda'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan email"
                                    v-model:value="formGeneral.email"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Password"
                                name="password"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Masukkan password anda'
                                    },
                                    {
                                        min: 8,
                                        message: 'Password minimal 8 karakter'
                                    },
                                    {
                                        max: 20,
                                        message: 'Password maksimal 20 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan password"
                                    v-model:value="formGeneral.password"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Nama Lengkap"
                                name="name"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Masukkan nama lengkap anda'
                                    },
                                    {
                                        min: 5,
                                        message:
                                            'Nama lengkap minimal 5 karakter'
                                    },
                                    {
                                        max: 50,
                                        message:
                                            'Nama lengkap maksimal 50 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan nama lengkap"
                                    v-model:value="formGeneral.name"
                                />
                            </a-form-item>

                            <!-- <a-form-item
                                label="No Induk"
                                name="no_induk"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Masukkan nomor induk anda'
                                    },
                                    {
                                        min: 5,
                                        message:
                                            'Nomor induk minimal 5 karakter'
                                    },
                                    {
                                        max: 20,
                                        message:
                                            'Nomor induk maksimal 20 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan nomor induk"
                                    v-model:value="formGeneral.no_induk"
                                />
                            </a-form-item> -->
                        </a-card>
                    </a-col>
                    <a-col span="12">
                        <a-card title="Data Alamat" :bordered="false">
                            <a-form-item
                                label="Alamat Lengkap"
                                name="alamat"
                                :rules="[
                                    {
                                        required: false,
                                        message: 'Masukkan alamat lengkap anda'
                                    },
                                    {
                                        min: 5,
                                        message:
                                            'Alamat lengkap minimal 5 karakter'
                                    },
                                    {
                                        max: 100,
                                        message:
                                            'Alamat lengkap maksimal 100 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan nama jalan"
                                    v-model:value="formGeneral.alamat"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Kelurahan"
                                name="kelurahan"
                                :rules="[
                                    {
                                        required: false,
                                        message: 'Masukkan kelurahan anda'
                                    },
                                    {
                                        min: 5,
                                        message: 'Kelurahan minimal 5 karakter'
                                    },
                                    {
                                        max: 50,
                                        message:
                                            'Kelurahan maksimal 50 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan kelurahan"
                                    v-model:value="formGeneral.kelurahan"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Kecamatan"
                                name="kecamatan"
                                :rules="[
                                    {
                                        required: false,
                                        message: 'Masukkan kecamatan anda'
                                    },
                                    {
                                        max: 50,
                                        message:
                                            'Kecamatan maksimal 50 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan kecamatan"
                                    v-model:value="formGeneral.kecamatan"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Kota"
                                name="kota"
                                :rules="[
                                    {
                                        max: 50,
                                        message: 'Kota maksimal 50 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan kota"
                                    v-model:value="formGeneral.kota"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Provinsi"
                                name="provinsi"
                                :rules="[
                                    {
                                        max: 50,
                                        message: 'Provinsi maksimal 50 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan provinsi"
                                    v-model:value="formGeneral.provinsi"
                                />
                            </a-form-item>
                        </a-card>
                    </a-col>
                </a-row>

                <a-row gutter="{16}">
                    <a-col span="12">
                        <a-card title="Data Pribadi" :bordered="false">
                            <a-form-item
                                label="Nomor Telepon"
                                name="no_telp"
                                :rules="[
                                    {
                                        min: 5,
                                        message:
                                            'Nomor telepon minimal 5 karakter'
                                    },
                                    {
                                        max: 20,
                                        message:
                                            'Nomor telepon maksimal 20 karakter'
                                    },
                                    {
                                        pattern: new RegExp('^[0-9]*$'),
                                        message:
                                            'Nomor telepon hanya boleh angka'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan nomor telepon"
                                    v-model:value="formGeneral.no_telp"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Golongan Darah"
                                name="gol_darah"
                                :rules="[
                                    {
                                        max: 5,
                                        message:
                                            'Golongan darah maksimal 5 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan golongan darah"
                                    v-model:value="formGeneral.gol_darah"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Tinggi"
                                name="tinggi"
                                :rules="[
                                    {
                                        max: 10,
                                        message: 'Tinggi maksimal 10 karakter'
                                    },
                                    {
                                        pattern: new RegExp('^[0-9]*$'),
                                        message: 'Tinggi hanya boleh angka'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan tingi badan"
                                    v-model:value="formGeneral.tinggi"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Berat"
                                name="berat"
                                :rules="[
                                    {
                                        max: 10,
                                        message: 'Berat maksimal 10 karakter'
                                    },
                                    {
                                        pattern: new RegExp('^[0-9]*$'),
                                        message: 'Berat hanya boleh angka'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan berat badan"
                                    v-model:value="formGeneral.berat"
                                />
                            </a-form-item>

                            <a-form-item label="Jenis Kelamin">
                                <a-select
                                    v-model:value="formGeneral.jenis_kelamin"
                                >
                                    <a-select-option value="laki-laki">
                                        Laki-laki
                                    </a-select-option>
                                    <a-select-option value="perempuan">
                                        Perempuan
                                    </a-select-option>
                                </a-select>
                            </a-form-item>

                            <a-form-item
                                label="Tempat Lahir"
                                name="tempat_lahir"
                                :rules="[
                                    {
                                        max: 50,
                                        message:
                                            'Tempat lahir maksimal 50 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan tempat lahir"
                                    v-model:value="formGeneral.tempat_lahir"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Tanggal Lahir"
                                name="tanggal_lahir"
                                :rules="[
                                    {
                                        max: 50,
                                        message:
                                            'Tanggal lahir maksimal 50 karakter'
                                    },
                                    {
                                        pattern: new RegExp(
                                            '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                        ),
                                        message:
                                            'Format tanggal lahir harus YYYY-MM-DD'
                                    }
                                ]"
                            >
                                <a-date-picker
                                    v-model:value="formGeneral.tanggal_lahir"
                                    value-format="YYYY-MM-DD"
                                    :style="{ width: '100%' }"
                                />
                            </a-form-item>
                        </a-card>
                    </a-col>
                    <a-col span="12">
                        <a-card
                            v-if="isSiswa"
                            title="Data Siswa"
                            :bordered="false"
                        >
                            <a-form-item
                                label="NISN"
                                name="nisn"
                                :rules="[
                                    {
                                        max: 50,
                                        message: 'NISN maksimal 50 karakter'
                                    },
                                    {
                                        required: true,
                                        message: 'NISN tidak boleh kosong'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan NISN"
                                    v-model:value="formGeneral.nisn"
                                />
                            </a-form-item>
                            <a-form-item
                                label="Kelas"
                                placeholder="Pilih Kelas"
                            >
                                <a-select
                                    @change="handleKelasChange"
                                    v-model:value="formGeneral.kelas_id"
                                >
                                    <a-select-option
                                        v-for="(kelas, index) in kelass"
                                        :key="kelas.id"
                                        :value="kelas.id"
                                    >
                                        {{ kelas.jenjang }}
                                        {{ kelas.section }}</a-select-option
                                    >
                                </a-select>
                            </a-form-item>

                            <a-form-item label="Beasiswa">
                                <a-select
                                    v-model:value="formGeneral.is_beasiswa"
                                    placeholder="Pilih Beasiswa"
                                >
                                    <a-select-option value="1"
                                        >Ya</a-select-option
                                    >
                                    <a-select-option value="0"
                                        >Tidak</a-select-option
                                    >
                                </a-select>
                            </a-form-item>

                            <a-form-item
                                label="Asal Sekolah"
                                name="asal_sekolah"
                                :rules="[
                                    {
                                        max: 50,
                                        message:
                                            'Asal sekolah maksimal 50 karakter'
                                    },
                                    {
                                        required: true,
                                        message:
                                            'Asal sekolah tidak boleh kosong'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan asal sekolah"
                                    v-model:value="formGeneral.asal_sekolah"
                                />
                            </a-form-item>
                        </a-card>

                        <a-card
                            v-if="isGuru"
                            title="Data Guru"
                            :bordered="false"
                        >
                            <a-form-item
                                label="NIP"
                                name="nip"
                                :rules="[
                                    {
                                        max: 50,
                                        message: 'NIP maksimal 50 karakter'
                                    },
                                    {
                                        required: true,
                                        message: 'NIP tidak boleh kosong'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan NISN"
                                    v-model:value="formGeneral.nip"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Gelar"
                                name="gelar"
                                :rules="[
                                    {
                                        max: 50,
                                        message: 'Gelar maksimal 50 karakter'
                                    }
                                ]"
                            >
                                <a-input
                                    placeholder="Masukkan gelar sekolah"
                                    v-model:value="formGeneral.gelar"
                                />
                            </a-form-item>

                            <a-form-item
                                label="Tanggal Bergabung"
                                name="tanggal_bergabung"
                                :rules="[
                                    {
                                        max: 50,
                                        message:
                                            'Tanggal bergabung maksimal 50 karakter'
                                    },
                                    {
                                        pattern: new RegExp(
                                            '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                        ),
                                        message:
                                            'Format tanggal lahir harus YYYY-MM-DD'
                                    }
                                ]"
                            >
                                <a-date-picker
                                    v-model:value="
                                        formGeneral.tanggal_bergabung
                                    "
                                    value-format="YYYY-MM-DD"
                                    :style="{ width: '100%' }"
                                />
                            </a-form-item>
                        </a-card>
                    </a-col>
                </a-row>

                <a-row gutter="{16}">
                    <a-col span="12"> </a-col>
                </a-row>

                <a-form-item>
                    <a-button type="primary" html-type="submit"
                        >Submit</a-button
                    >
                </a-form-item>
            </a-form>
        </a-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            visibleBulkInputModal: false,
            formGeneral: {
                username: null,
                name: null,
                no_induk: null,
                image: null,
                alamat: null,
                kelurahan: null,
                kecamatan: null,
                kota: null,
                provinsi: null,
                gol_darah: null,
                tinggi: null,
                berat: null,
                jenis_kelamin: 'laki-laki',
                tempat_lahir: null,
                tanggal_lahir: null,
                email: null,
                password: null,
                no_telp: null
            },
            kelass: [],
            rules: {
                username: [
                    {
                        required: true,
                        message: 'Username tidak boleh kosong',
                        trigger: 'blur'
                    },
                    {
                        min: 5,
                        message: 'Username minimal 5 karakter',
                        trigger: 'blur'
                    }
                ]
            },
            loading: false,
            role: this.$route.params.role
        }
    },
    computed: {
        isSiswa() {
            return this.$route.params.role == 'siswa'
        },
        isGuru() {
            return this.$route.params.role == 'guru'
        },
        isAdmin() {
            return this.$route.params.role == 'admin'
        }
    },
    mounted() {
        const vm = this
        if (vm.$route.params.role == 'siswa') {
            const siswaAttribute = {
                nisn: null,
                kelas_id: 1,
                is_beasiswa: '0',
                asal_sekolah: null
            }

            vm.formGeneral = { ...vm.formGeneral, ...siswaAttribute }

            vm.getKelas()
        }

        if (vm.$route.params.role == 'guru') {
            const guruAttribute = {
                nip: null,
                gelar: null,
                tanggal_bergabung: null
            }

            vm.formGeneral = { ...vm.formGeneral, ...guruAttribute }

            vm.getKelas()
        }
    },
    methods: {
        writeData() {
            const vm = this
            vm.loading = true
            const params = {
                req: 'add',
                role: vm.$route.params.role,
                ...vm.formGeneral
            }
            vm.axios
                .post(vm.url('user/write'), params)
                .then((response) => {
                    vm.loading = false
                    if ((response.status = 200)) {
                        vm.$notification.success({
                            message: 'Berhasil',
                            description: 'Data berhasil ditambahkan'
                        })
                    } else {
                        vm.$notification.error({
                            message: 'Kesalahan',
                            description:
                                'Telah terjadi kesalahan. Silahkan coba lagi'
                        })
                    }

                    if (vm.$route.params.role == 'siswa') {
                        vm.formGeneral = {
                            username: null,
                            name: null,
                            no_induk: null,
                            image: null,
                            alamat: null,
                            kelurahan: null,
                            kecamatan: null,
                            kota: null,
                            provinsi: null,
                            gol_darah: null,
                            tinggi: null,
                            berat: null,
                            jenis_kelamin: 'laki-laki',
                            tempat_lahir: null,
                            tanggal_lahir: null,
                            email: null,
                            password: null,
                            no_telp: null,
                            nisn: null,
                            kelas_id: 1,
                            is_beasiswa: '0',
                            asal_sekolah: null
                        }
                    }

                    if (vm.$route.params.role == 'guru') {
                        vm.formGeneral = {
                            username: null,
                            name: null,
                            no_induk: null,
                            image: null,
                            alamat: null,
                            kelurahan: null,
                            kecamatan: null,
                            kota: null,
                            provinsi: null,
                            gol_darah: null,
                            tinggi: null,
                            berat: null,
                            jenis_kelamin: 'laki-laki',
                            tempat_lahir: null,
                            tanggal_lahir: null,
                            email: null,
                            password: null,
                            no_telp: null,
                            nip: null,
                            gelar: null,
                            tanggal_bergabung: null
                        }
                    }

                    if (vm.$route.params.role == 'admin') {
                        vm.formGeneral = {
                            username: null,
                            name: null,
                            no_induk: null,
                            image: null,
                            alamat: null,
                            kelurahan: null,
                            kecamatan: null,
                            kota: null,
                            provinsi: null,
                            gol_darah: null,
                            tinggi: null,
                            berat: null,
                            jenis_kelamin: 'laki-laki',
                            tempat_lahir: null,
                            tanggal_lahir: null,
                            email: null,
                            password: null,
                            no_telp: null
                        }
                    }

                    window.scrollTo(0, 0)
                })
                .catch((e) => {
                    console.log(JSON.stringify(e.response.data.errors))
                    this.$notification.error({
                        message: e.response.data.message,
                        description: JSON.stringify(e.response.data.errors)
                    })
                })
        },
        getKelas() {
            const vm = this
            vm.axios
                .get(vm.url('kelas/read'), { params: { req: 'all' } })
                .then((response) => {
                    vm.kelass = response.data.models
                })
                .catch((e) => vm.$onAjaxError(e))
        },
        handleKelasChange(value) {
            const vm = this
            vm.formGeneral.kelas_id = value
        },

        onFinish() {
            const vm = this
            vm.writeData()
        },
        bulkInputClick() {
            this.visibleBulkInputModal = true
        }
    }
}
</script>
