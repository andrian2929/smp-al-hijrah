<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card
                :loading="loading"
                title="INFORMASI KELAS"
                style="width: 100%"
            >
            </a-card>
        </a-col>
    </a-row>

    <div style="background-color: #ececec; padding: 20px">
        <a-row :gutter="16">
            <a-col :span="24">
                <a-card style="border-radius: 20px">
                    <a-skeleton :loading="loading">
                        <a-list
                            size="small"
                            bordered
                            :data-source="data"
                            style="border-radius: 8px"
                        >
                            <a-list-item>
                                <strong
                                    >Kelas : {{ kelasData.jenjang }} -
                                    {{ kelasData.section }}</strong
                                >
                            </a-list-item>
                            <a-list-item>
                                <strong
                                    >Tahun Ajaran : 2022-2023 (Ganjil)</strong
                                >
                            </a-list-item>
                            <a-list-item>
                                <strong
                                    >Wali Kelas :
                                    {{ kelasData.wali_kelas.name }}</strong
                                >
                            </a-list-item>
                        </a-list>
                    </a-skeleton>
                </a-card>
            </a-col>

            <a-col :span="24" style="margintop: 20px">
                <a-card style="border-radius: 20px" title="Roster Kelas">
                    <a-skeleton :loading="loading">
                        <div
                            class="schedule-table"
                            v-for="hari in [
                                'senin',
                                'selasa',
                                'rabu',
                                'kamis',
                                'jumat',
                                'sabtu'
                            ]"
                        >
                            <strong>
                                {{
                                    hari.charAt(0).toUpperCase() + hari.slice(1)
                                }}
                            </strong>
                            <a-table
                                style="margin-top: 10px"
                                :dataSource="dataSource(hari)"
                                :columns="columns"
                                :pagination="false"
                            />
                        </div>
                    </a-skeleton>
                </a-card>
            </a-col>
        </a-row>
    </div>
</template>

<script>
export default {
    data() {
        return {
            columns: [
                {
                    title: 'Waktu',
                    dataIndex: 'waktu',
                    key: 'waktu',
                    width: 100
                },
                {
                    title: 'Mata Pelajaran',
                    dataIndex: 'matapelajaran',
                    key: 'matapelajaran'
                },
                {
                    title: 'Guru',
                    dataIndex: 'guru',
                    key: 'guru',
                    width: 300
                }
            ],
            loading: true,
            kelasData: null
        }
    },
    created() {
        this.readData()
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
                            const kelas_id = response.data.models.kelas.id
                            vm.axios
                                .get(vm.url('kelas/read'), {
                                    params: {
                                        id: kelas_id,
                                        req: 'single'
                                    }
                                })
                                .then((response) => {
                                    vm.kelasData = response.data.models
                                })
                                .catch((e) => vm.$onAjaxError(e))
                            vm.axios

                                .get(vm.url('kelas/read'), {
                                    params: {
                                        kelas_id: kelas_id,
                                        req: 'get_roster'
                                    }
                                })
                                .then((response) => {
                                    vm.kelasData = {
                                        ...vm.kelasData,
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
            let roster = this.kelasData.roster.filter(
                (item) => item.hari === hari
            )
            roster.sort((a, b) => a.waktu.localeCompare(b.waktu))
            return roster.map((item, index) => {
                return {
                    key: index + 1,
                    waktu: item.waktu.substring(0, 5),
                    matapelajaran: item.mapel.name,
                    guru: item.guru.name
                }
            })
        }
    }
}
</script>

<style scoped>
.schedule-table {
    margin-bottom: 30px;
}
</style>
