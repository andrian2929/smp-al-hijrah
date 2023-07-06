<template>
    <h1 style="margin-left: 30px">Laporan Kehadiran</h1>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card title="Tanggal" style="width: 100%; margin-bottom: 20px">
                <a-form
                    :model="formKehadiran"
                    @finish="readKehadiran"
                    ref="formKehadiran"
                >
                    <a-space
                        style="
                            display: flex;
                            justify-content: flex-container;
                            flex-wrap: wrap;
                        "
                    >
                        <a-form-item
                            name="tanggal"
                            label="Waktu :"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Pilih waktu terlebih dahulu'
                                }
                            ]"
                        >
                            <a-date-picker
                                placeholder="Pilih waktu"
                                value-format="YYYY-MM"
                                picker="month"
                                style="width: 100%"
                                v-model:value="formKehadiran.tanggal"
                            />
                        </a-form-item>

                        <a-form-item>
                            <a-button
                                type="primary"
                                html-type="submit"
                                style="width: 100%"
                            >
                                Cari
                            </a-button>
                        </a-form-item>
                    </a-space>
                </a-form>
            </a-card>

            <a-card title="Data Kehadiran">
                <a-table
                    :columns="kehadiranColumns"
                    :dataSource="kehadiranData"
                >
                    <template #bodyCell="{ column, record }"></template>
                </a-table>
            </a-card>
        </a-col>
    </a-row>
</template>

<script>
import moment from 'moment'
import 'moment/locale/id'

const kehadiranColumns = [
    {
        key: 'no',
        dataIndex: 'no',
        title: 'No'
    },
    {
        key: 'tanggal',
        dataIndex: 'tanggal',
        title: 'Tanggal'
    },
    {
        key: 'status',
        dataIndex: 'status',
        title: 'Status'
    },
    {
        key: 'keterangan',
        dataIndex: 'keterangan',
        title: 'Keterangan'
    }
]
export default {
    data() {
        return {
            formKehadiran: {
                tanggal: moment().format('YYYY-MM')
            },
            kehadiranColumns,
            kehadiranData: []
        }
    },
    methods: {
        readKehadiran() {
            this.axios
                .get(this.url('user'))
                .then((res) => {
                    const params = {
                        siswa: res.data.id,
                        month: this.formKehadiran.tanggal.split('-')[1],
                        year: this.formKehadiran.tanggal.split('-')[0],
                        req: 'by_siswa'
                    }

                    this.axios
                        .get(this.url('kehadiran/read'), { params })
                        .then((res) => {
                            const data = res.data

                            const length = Object.keys(data).length

                            for (let i = 1; i <= length; i++) {
                                const item = data[i][0]

                                const row = {
                                    no: i,
                                    tanggal:
                                        item.kehadiran.length > 0
                                            ? item.kehadiran[0].tanggal
                                            : 'Belum tersedia',
                                    status:
                                        item.kehadiran.length > 0
                                            ? this.capitalizeFirstLetter(
                                                  item.kehadiran[0].kehadiran
                                              )
                                            : 'Belum tersedia',
                                    keterangan:
                                        item.kehadiran.length > 0
                                            ? item.kehadiran[0].keterangan
                                            : null
                                }
                                this.kehadiranData.push(row)
                            }

                            console.log(this.kehadiranData)
                        })
                        .catch((err) => {})
                })
                .catch((err) => {})
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1)
        }
    }
}
</script>
