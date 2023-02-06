<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card title="Dashboard" style="width: 100%; border-radius: 20px">
                <div style="padding: 20px">
                    <a-row :gutter="16">
                        <a-col :span="8">
                            <a-spin :spinning="loading">
                                <a-card
                                    size="small"
                                    style="
                                        background-color: #13005a;
                                        border-radius: 15px;
                                        height: 100px;
                                    "
                                >
                                    <h1 style="color: #ffffff; margin: 0">
                                        {{ guruCount }}
                                    </h1>
                                    <h5 style="color: #ffffff; margin: 0">
                                        Guru
                                    </h5>
                                </a-card>
                            </a-spin>
                        </a-col>
                        <a-col :span="8">
                            <a-spin :spinning="loading">
                                <a-card
                                    size="small"
                                    style="
                                        background-color: #00337c;
                                        border-radius: 15px;
                                        height: 100px;
                                    "
                                >
                                    <h1 style="color: #ffffff; margin: 0">
                                        {{ siswaCount }}
                                    </h1>
                                    <h5 style="color: #ffffff; margin: 0">
                                        Guru
                                    </h5>
                                </a-card>
                            </a-spin>
                        </a-col>
                        <a-col :span="8">
                            <a-spin :spinning="loading">
                                <a-card
                                    size="small"
                                    style="
                                        background-color: #03c988;
                                        border-radius: 15px;
                                        height: 100px;
                                    "
                                >
                                    <h1 style="color: #ffffff; margin: 0">0</h1>
                                    <h5 style="color: #ffffff; margin: 0">
                                        Online User
                                    </h5>
                                </a-card>
                            </a-spin>
                        </a-col>
                    </a-row>
                </div>
            </a-card>
        </a-col>
    </a-row>

    <div style="padding: 20px">
        <a-row :gutter="16">
            <a-col :span="12">
                <a-card :bordered="false">
                    <h3>Data Guru</h3>
                    <pie-chart-outlined style="fontsize: 1000%; align: end" />
                </a-card>
            </a-col>

            <a-col :span="12">
                <a-card :bordered="false">
                    <h3>Data Siswa</h3>
                    <pie-chart-outlined style="fontsize: 1000%; align: end" />
                </a-card>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            siswaCount: null,
            guruCount: null,
            loading: true
        }
    },

    created() {
        this.loadData()
    },
    mounted() {},

    methods: {
        // getSiswaCount() {
        //     axios
        //         .get(this.url('siswa/read'), { params: { req: 'count' } })
        //         .then((res) => {
        //             this.siswaCount = res.data.models
        //         })
        // },

        // getGuruCount() {
        //     axios
        //         .get(this.url('guru/read'), { params: { req: 'count' } })
        //         .then((res) => {
        //             this.guruCount = res.data.models
        //         })
        // }
        async loadData() {
            const siswaResponse = await axios.get(this.url('siswa/read'), {
                params: { req: 'count' }
            })
            this.siswaCount = siswaResponse.data.models

            const guruResponse = await axios.get(this.url('guru/read'), {
                params: { req: 'count' }
            })
            this.guruCount = guruResponse.data.models
            this.loading = false
        }
    }
}
</script>
