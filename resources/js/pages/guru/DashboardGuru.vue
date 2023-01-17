<template>

  <a-row type="flex" justify="center">
    <a-col :xs="23">
        <a-card :loading="loading" title="DASHBOARD" style="width: 100%">
        </a-card>
    </a-col>
  </a-row>

<div style="background-color: #ececec; padding: 20px">
    <a-row :gutter="16">
      <a-col :span="8">
        <a-card >
            <div align="center">
                <img
                    style="width: 50%"
                    src="/img/no_profile.png"
                    alt="profile picture"
                />
                <h3 style="marginTop: 30px; marginBottom: 30px">Guru</h3>
            </div>
                <a-row :gutter="[8, 8]">
                  <a-col :span="12">
                    <strong>ID Staff</strong>
                  </a-col>
                  <a-col :span="12">
                    <p>130686</p>
                  </a-col>
                  <a-col :span="12">
                    <strong>Posisi</strong>
                  </a-col>
                  <a-col :span="12">
                    <p>Guru</p>
                  </a-col>
                  <a-col :span="12">
                    <strong>Gelar</strong>
                  </a-col>
                  <a-col :span="12">
                    <p>S.Pd</p>
                  </a-col>
                  <a-col :span="12">
                    <strong>Departemen</strong>
                  </a-col>
                  <a-col :span="12">
                    PKN
                  </a-col>
                  <a-col :span="12">
                    <strong>Tanggal Bergabung</strong>
                  </a-col>
                  <a-col :span="12">
                    2012-12-12
                  </a-col>
                </a-row>
        </a-card>
      </a-col>

      <a-col :span="16">
        <a-card>
          <a-table :dataSource="dataSource" :columns="columns" :pagination="false" />
        </a-card>
        <a-card style="marginTop: 20px">
          <h4 style="marginBottom: 20px">Kehadiran : </h4>
          <a-row :gutter="[8, 8]">
                  <a-col :span="6">
                    <star-filled style="fontSize: 150%; color: green"/>
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
                    <star-filled style="fontSize: 150%; color: blue"/>
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
                    <star-filled style="fontSize: 150%; color: orange"/>
                  </a-col>
                  <a-col :span="6">
                    <strong>1</strong>
                  </a-col>
                  <a-col :span="6">
                    <strong>:</strong>
                  </a-col>
                  <a-col :span="6">
                    <strong>Bertugas Keluar</strong>
                  </a-col>
                  <a-col :span="6">
                    <star-filled style="fontSize: 150%; color: yellow"/>
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
                    <star-filled style="fontSize: 150%; color: red"/>
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
import { defineComponent } from 'vue';
import {
  StarFilled,
} from '@ant-design/icons-vue';
  export default defineComponent({
    components: {
    StarFilled,
    },
    setup() {
      return {
        dataSource: [
            {
            key: '1',
            matapelajaran: 'PKN',
            kelas: 'VII',
            hari: 'Senin',
            waktu: '8.00 - 9.30'
          },
          {
            key: '2',
            matapelajaran: 'IPA',
            kelas: 'VII',
            hari: 'Senin',
            waktu: '8.00 - 9.30'
          },
          {
            key: '3',
            matapelajaran: 'IPS',
            kelas: 'VII',
            hari: 'Senin',
            waktu: '8.00 - 9.30'
          },
        ],

        columns: [
          {
            title: 'Mata Pelajaran',
            dataIndex: 'matapelajaran',
            key: 'matapelajaran',
          },
          {
            title: 'Kelas',
            dataIndex: 'kelas',
            key: 'kelas',
          },
          {
            title: 'Hari',
            dataIndex: 'hari',
            key: 'hari',
          },
          {
            title: 'Waktu',
            dataIndex: 'waktu',
            key: 'waktu',
          },
        ],
      };
    },
  });
</script>