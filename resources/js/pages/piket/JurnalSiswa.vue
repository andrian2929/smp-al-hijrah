<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <h1>Jurnal Siswa</h1>
            <a-card>
                <a-button type="primary">Tambah</a-button>
                <div style="overflow: auto">
                    <a-table
                        :data-source="jurnalSiswaDataSources"
                        :columns="jurnalSiswaColumns"
                        style="margin-top: 10px"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'aksi'">
                                <a-space>
                                    <a-button type="primary" @click="">
                                        <template #icon
                                            ><EditOutlined
                                        /></template>
                                    </a-button>
                                    <a-button type="danger" @click="">
                                        <template #icon
                                            ><DeleteOutlined
                                        /></template>
                                    </a-button>
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-card>
        </a-col>
    </a-row>
</template>
<script>
const jurnalSiswaDataSources = [
    {
        no: '1',
        nama: 'Budi',
        kehadiran: 'Hadir',
        tanggal: '12 November 2022',
        jam: '12:00',
        keterangan: 'Terlambat'
    },
    {
        no: '2',
        nama: 'Budi',
        kehadiran: 'Hadir',
        tanggal: '12 November 2022',
        jam: '12:00',
        keterangan: 'Terlambat'
    }
]

const jurnalSiswaColumns = [
    {
        title: 'No',
        dataIndex: 'no'
    },
    {
        title: 'Nama',
        dataIndex: 'nama'
    },
    {
        kehadiran: 'Kehadiran',
        dataIndex: 'kehadiran'
    },
    {
        title: 'Tanggal',
        dataIndex: 'tanggal'
    },
    {
        title: 'Jam',
        dataIndex: 'jam'
    },
    {
        title: 'Keterangan',
        dataIndex: 'keterangan'
    },
    {
        title: 'Aksi',
        dataIndex: 'aksi'
    }
]

import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue'

export default {
    data() {
        return {
            jurnalSiswaDataSources,
            jurnalSiswaColumns
        }
    }
}
</script>
