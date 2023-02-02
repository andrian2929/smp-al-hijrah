<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card title="Dashboard" style="width: 100%"> </a-card>
        </a-col>
    </a-row>
    <div style="background-color: #ececec; padding: 20px">
        <a-row :gutter="16">
            <a-col :sm="24" :xl="8" :md="12" :lg="8">
                <a-card style="border-radius: 20px">
                    <a-skeleton :loading="loading">
                        <div align="center">
                            <img
                                style="
                                    width: 50%;
                                    border-radius: 10px;
                                    margin-bottom: 20px;
                                "
                                :src="
                                    userData.user.image == 'default.png'
                                        ? '/img/no_profile.png'
                                        : '/img/profile_photo/' +
                                          userData.user.image
                                "
                            />
                            <h3 style="margintop: 30px; marginbottom: 30px">
                                {{ userData.user.name }}
                            </h3>
                        </div>
                        <a-row :gutter="[8, 8]">
                            <a-col :span="12">
                                <strong>NIP</strong>
                            </a-col>
                            <a-col :span="12">
                                <p>{{ userData.nip }}</p>
                            </a-col>
                            <a-col :span="12">
                                <strong>Posisi</strong>
                            </a-col>
                            <a-col :span="12">
                                <p>{{ userData.user.roles[0].name }}</p>
                            </a-col>
                            <a-col :span="12">
                                <strong>Gelar</strong>
                            </a-col>
                            <a-col :span="12">
                                <p>{{ userData.gelar }}</p>
                            </a-col>

                            <a-col :span="12">
                                <strong>Tanggal Bergabung</strong>
                            </a-col>
                            <a-col :span="12">
                                {{ humanizeDate }}
                            </a-col>
                        </a-row>
                    </a-skeleton>
                </a-card>
            </a-col>

            <a-col :sm="24" :lg="16">
                <a-card
                    :title="`Mata Pelajaran Hari Ini`"
                    style="border-radius: 20px"
                >
                    <a-skeleton :loading="loading">
                        <a-table
                            :dataSource="dataSource(thisDay)"
                            :columns="columns"
                            :pagination="false"
                        />
                    </a-skeleton>
                </a-card>
                <a-card style="margintop: 20px; border-radius: 20px">
                    <h4 style="marginbottom: 20px">Kehadiran :</h4>
                    <a-row :gutter="[8, 8]">
                        <a-col :span="6">
                            <star-filled style="fontsize: 150%; color: green" />
                        </a-col>
                        <a-col :span="6">
                            <strong>12</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>:</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>Hadir</strong>
                        </a-col>
                        <a-col :span="6">
                            <star-filled style="fontsize: 150%; color: blue" />
                        </a-col>
                        <a-col :span="6">
                            <strong>2</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>:</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>Izin</strong>
                        </a-col>
                        <a-col :span="6">
                            <star-filled
                                style="fontsize: 150%; color: orange"
                            />
                        </a-col>
                        <a-col :span="6">
                            <strong>1</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>:</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>Mengikuti Kegiatan di Luar</strong>
                        </a-col>
                        <a-col :span="6">
                            <star-filled
                                style="fontsize: 150%; color: yellow"
                            />
                        </a-col>
                        <a-col :span="6">
                            <strong>1</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>:</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>Sakit</strong>
                        </a-col>
                        <a-col :span="6">
                            <star-filled style="fontsize: 150%; color: red" />
                        </a-col>
                        <a-col :span="6">
                            <strong>0</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>:</strong>
                        </a-col>
                        <a-col :span="6">
                            <strong>Terlambat</strong>
                        </a-col>
                    </a-row>
                </a-card>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import moment from 'moment'
import 'moment/locale/id'

const scheduleColumn = [
    {
        title: 'Mata Pelajaran',
        dataIndex: 'matapelajaran',
        key: 'matapelajaran'
    },
    {
        title: 'Kelas',
        dataIndex: 'kelas',
        key: 'kelas'
    },
    {
        title: 'Hari',
        dataIndex: 'hari',
        key: 'hari'
    },
    {
        title: 'Waktu',
        dataIndex: 'waktu',
        key: 'waktu'
    }
]

export default {
    data() {
        return {
            scheduleColumn,
            scheduleDataSource: [
                {
                    key: '1',
                    matapelajaran: 'Bahasa Indonesia',
                    kelas: 'XII IPA 1',
                    hari: 'Senin',
                    waktu: '07.00 - 08.00'
                },
                {
                    key: '2',
                    matapelajaran: 'Bahasa Indonesia',
                    kelas: 'XII IPA 1',
                    hari: 'Selasa',
                    waktu: '07.00 - 08.00'
                },
                {
                    key: '3',
                    matapelajaran: 'Bahasa Indonesia',
                    kelas: 'XII IPA 1',
                    hari: 'Rabu',
                    waktu: '07.00 - 08.00'
                },
                {
                    key: '4',
                    matapelajaran: 'Bahasa Indonesia',
                    kelas: 'XII IPA 1',
                    hari: 'Kamis',
                    waktu: '07.00 - 08.00'
                },
                {
                    key: '5',
                    matapelajaran: 'Bahasa Indonesia',
                    kelas: 'XII IPA 1',
                    hari: 'Jumat',
                    waktu: '07.00 - 08.00'
                }
            ],
            userData: null,
            loading: true
        }
    },
    created() {
        this.readData()
    },
    computed: {
        humanizeDate() {
            return moment(this.userData.tanggal_bergabung).format(
                'dddd, DD MMMM YYYY'
            )
        }
    },
    methods: {
        readData() {
            this.axios.get(this.url('user')).then((response) => {
                this.axios
                    .get(this.url('guru/read'), {
                        params: {
                            user_id: response.data.id,
                            req: 'get_guru_by_user_id'
                        }
                    })
                    .then((response) => {
                        this.userData = response.data.models
                        this.loading = false
                    })
            })
        }
    }
}
</script>
