<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card title="Dashboard" style="width: 100%"> </a-card>
        </a-col>
    </a-row>

    <div style="background-color: #ececec; padding: 20px">
        <a-row :gutter="16">
            <a-col :sm="24" :xl="8" :md="12" :lg="8">
                <a-card title="Biodata" style="border-radius: 20px">
                    <a-skeleton :loading="loading">
                        <div align="center">
                            <img
                                style="
                                    width: 50%;
                                    border-radius: 10px;
                                    margin-bottom: 20px;
                                "
                                :src="
                                    siswa.user.image == 'default.png'
                                        ? '/img/no_profile.png'
                                        : '/img/profile_photo/' +
                                          siswa.user.image
                                "
                                alt="profile picture"
                            />
                            <h3 style="margintop: 30px">
                                {{ siswa.user.name }}
                            </h3>
                        </div>
                        <a-row :gutter="[8, 8]">
                            <a-col :span="12">
                                <strong>NISN</strong>
                            </a-col>
                            <a-col :span="12">
                                <p>{{ siswa.nisn }}</p>
                            </a-col>
                            <a-col :span="12">
                                <strong>Kelas</strong>
                            </a-col>
                            <a-col :span="12">
                                <p>
                                    {{ siswa.kelas.jenjang }} -
                                    {{ siswa.kelas.section }}
                                </p>
                            </a-col>
                            <a-col :span="12">
                                <strong>Beasiswa</strong>
                            </a-col>
                            <a-col :span="12">
                                <p>
                                    {{
                                        siswa.is_beasiswa == 0 ? 'Tidak' : 'Ya'
                                    }}
                                </p>
                            </a-col>
                            <a-col :span="12">
                                <strong>Tanggal Bergabung</strong>
                            </a-col>
                            <a-col :span="12">
                                <span>{{ humanizeDate }}</span>
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
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import { StarFilled } from '@ant-design/icons-vue'

import axios from 'axios'
import moment from 'moment'
import 'moment/locale/id'

export default defineComponent({
    components: {
        StarFilled
    },
    data() {
        return {
            columns: [
                {
                    title: 'Nama',
                    dataIndex: 'matapelajaran',
                    key: 'matapelajaran'
                },

                {
                    title: 'Guru',
                    dataIndex: 'guru',
                    key: 'guru'
                },
                {
                    title: 'Waktu mulai',
                    dataIndex: 'waktu_mulai',
                    key: 'waktu_mulai'
                },
                {
                    title: 'Waktu selesai',
                    dataIndex: 'waktu_selesai',
                    key: 'waktu_mulai'
                }
            ],
            siswa: null,
            loading: true
        }
    },

    created() {
        this.readData()
    },

    computed: {
        humanizeDate() {
            return moment(this.siswa.tanggal_bergabung).format(
                'dddd, DD MMMM YYYY'
            )
        },
        thisDay() {
            return moment().format('dddd')
        }
    },

    methods: {
        readData() {
            const vm = this
            vm.axios
                .get(vm.url('user'))
                .then((response) => {
                    vm.axios
                        .get(vm.url('siswa/read'), {
                            params: {
                                user_id: response.data.id,
                                req: 'get_siswa_by_user_id'
                            }
                        })
                        .then((response) => {
                            vm.siswa = response.data.models
                            vm.siswa.created_at = moment(
                                vm.siswa.created_at
                            ).format('DD MMMM YYYY')
                            vm.siswa.created_at_humanize = moment(
                                vm.siswa.created_at
                            )
                                .startOf('day')
                                .fromNow()

                            vm.axios
                                .get(vm.url('kelas/read'), {
                                    params: {
                                        kelas_id: response.data.models.kelas.id,
                                        req: 'get_roster'
                                    }
                                })
                                .then((response) => {
                                    vm.siswa = {
                                        ...vm.siswa,
                                        roster: response.data.models
                                    }
                                    vm.loading = false
                                })
                                .catch((e) => vm.$onAjaxError(e))
                        })
                        .catch((e) => vm.$onAjaxError(e))
                })
                .catch((error) => {
                    vm.$onAjaxError(error)
                })
        },
        dataSource(hari) {
            let roster = this.siswa.roster.filter(
                (item) => item.hari == hari.toLowerCase()
            )
            roster.sort((a, b) => a.waktu_mulai.localeCompare(b.waktu_mulai))
            return roster.map((item, index) => {
                return {
                    key: index + 1,
                    matapelajaran: item.mapel.name,
                    guru: item.guru.name,
                    waktu_mulai: `${item.waktu_mulai.substring(0, 5)}`,
                    waktu_selesai: `${item.waktu_selesai.substring(0, 5)}`
                }
            })
        }
    }
})
</script>
