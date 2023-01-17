<template>
    
<a-row type="flex" justify="center">
    <a-col :xs="23">
        <a-card :loading="loading" title="INFORMASI KELAS" style="width: 100%">
        </a-card>
    </a-col>
</a-row>

<div style="background-color: #ececec; padding: 20px">
    <a-row :gutter="16">
        <a-col :span="24">
            <a-card>
                <a-list size="small" bordered :data-source="data">
                    <a-list-item>
                        <strong>Kelas : VII-A</strong>
                    </a-list-item>
                    <a-list-item>
                        <strong>Tahun Ajaran : 2022-2023 (Ganjil)</strong>
                    </a-list-item>
                    <a-list-item>
                        <strong>Wali Kelas : Roslyn Ernser V</strong>
                    </a-list-item>
                </a-list>
            </a-card>
        </a-col>

        <a-col :span="24" style="marginTop: 20px">
            <h3>Roster Kelas</h3>
            <a-card>
                <strong>Senin</strong>
                <a-table :dataSource="dataSource" :columns="columns" :pagination="false"/>
                <strong>Selasa</strong>
                <a-table :dataSource="dataSource" :columns="columns" :pagination="false"/>
                <strong>Rabu</strong>
                <a-table :dataSource="dataSource" :columns="columns" :pagination="false"/>
                <strong>Kamis</strong>
                <a-table :dataSource="dataSource" :columns="columns" :pagination="false"/>
                <strong>Jumat</strong>
                <a-table :dataSource="dataSource" :columns="columns" :pagination="false"/>
            </a-card>
        </a-col>
    </a-row>
</div>

</template>

<script>
  export default {
    setup() {
      return {
        dataSource: [
          {
            key: '1',
            waktu: '8.00 - 9.30',
            matapelajaran: 'IPA',
            guru: 'Carroll Hilpert V',
          },
          {
            key: '2',
            waktu: '10.15 - 10.50',
            matapelajaran: 'PKN',
            guru: 'Ava Kling',
          },
          {
            key: '2',
            waktu: '10.50 - 11.35',
            matapelajaran: 'IPS',
            guru: 'Idell Fahey',
          },
        ],

        columns: [
          {
            title: 'Waktu',
            dataIndex: 'waktu',
            key: 'waktu',
          },
          {
            title: 'Mata Pelajaran',
            dataIndex: 'matapelajaran',
            key: 'matapelajaran',
          },
          {
            title: 'Guru',
            dataIndex: 'guru',
            key: 'guru',
          },
        ],
      };
    },
  };
</script>