<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <h1>Kehadiran Tamu</h1>
            <a-card>
                <a-button type="primary">Tambah</a-button>
                <div style="overflow: auto">
                    <a-table
                        :data-source="janjiTemuTamuDataSources"
                        :columns="janjiTemuTamuColumns"
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
const janjiTemuTamuColumns = [
    {
        title: 'No',
        dataIndex: 'no'
    },
    {
        title: 'Nama Tamu',
        dataIndex: 'namaTamu'
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
        title: 'Tujuan',
        dataIndex: 'tujuan'
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

const janjiTemuTamuDataSources = [
    {
        no: '1',
        tujuan: 'Budi',
        tanggal: '12 November 2022',
        jam: '12:00',
        namaTamu: 'Budi',
        keterangan: 'Ingin berjumpa dengan kepala sekolah'
    },
    {
        no: '2',
        tujuan: 'Budi',
        tanggal: '12 November 2022',
        jam: '12:00',
        namaTamu: 'Budi',
        keterangan: 'Ingin berjumpa dengan kepala sekolah'
    }
]

import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue'

export default {
    data() {
        return {
            janjiTemuTamuDataSources,
            janjiTemuTamuColumns
        }
    }
}
</script>
