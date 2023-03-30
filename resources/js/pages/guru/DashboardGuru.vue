<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card title="Dashboard" style="width: 100%; border-radius: 10px">
            </a-card>
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
                            <h3 style="margintop: 30px; margin-bottom: 30px">
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
                    :title="`Mata Pelajaran`"
                    style="border-radius: 20px; margin-bottom: 20px"
                >
                    <a-skeleton :loading="loading">
                        <a-table
                            :dataSource="scheduleDataSource"
                            :columns="scheduleColumn"
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
        title: 'No',
        dataIndex: 'key',
        key: 'key'
    },
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
        title: 'Waktu Mulai',
        dataIndex: 'waktu_mulai',
        key: 'waktu_mulai'
    },
    {
        title: 'Waktu Selesai',
        dataIndex: 'waktu_selesai',
        key: 'waktu_selesai'
    }
]

export default {
    data() {
        return {
            scheduleColumn,
            scheduleDataSource: [],
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
        },

        thisDay() {
            return moment().format('dddd')
        }
    },
    methods: {
        readData() {
            this.axios.get(this.url('user')).then((response) => {
                const user_id = response.data.id
                this.axios
                    .get(this.url('guru/read'), {
                        params: {
                            user_id: user_id,
                            req: 'get_guru_by_user_id'
                        }
                    })
                    .then((response) => {
                        this.userData = response.data.models
                    })

                this.axios
                    .get(this.url('mapel/read'), {
                        params: {
                            guru_id: user_id,
                            req: 'get_mapel_by_guru_id'
                        }
                    })
                    .then((response) => {
                        this.loading = false
                        this.readMapel(response.data.models)
                    })
            })
        },
        readMapel(response) {
            const days = [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
                'minggu'
            ]
            days.forEach((day, index) => {
                let arrayTemp = []
                response.forEach((item, index) => {
                    if (item.hari === day) {
                        arrayTemp.push({
                            key: index + 1,
                            matapelajaran: item.mapel.name,
                            kelas:
                                item.kelas.jenjang + ' - ' + item.kelas.section,
                            hari: `${item.hari
                                .charAt(0)
                                .toUpperCase()}${item.hari.slice(1)}`,
                            waktu_mulai: item.waktu_mulai,
                            waktu_selesai: item.waktu_selesai
                        })
                    }
                    arrayTemp.sort((a, b) => {
                        return a.waktu_mulai.localeCompare(b.waktu_mulai)
                    })
                })
                this.scheduleDataSource.push(...arrayTemp)
                this.scheduleDataSource.map((item, index) => {
                    item.key = index + 1
                    item.waktu_mulai = item.waktu_mulai.substring(0, 5)
                    item.waktu_selesia = item.waktu_selesai.substring(0, 5)
                })
            })
        }
    }
}
</script>
