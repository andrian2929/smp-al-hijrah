<template>
  <h1 style="margin-left: 30px">Tugas Siswa</h1>
  <a-row type="flex" justify="center">
    <a-col :xs="23">
      <a-card>
        <a-table :dataSource="dataSource" :columns="columns" :pagination="false"/>
      </a-card>
    </a-col>
  </a-row>
</template>

<script>
export default {
    setup() {
      return {
        dataSource: [
          {
            key: '1',
            subjek: 'IPS',
            judul: 'Sejarah Indonesia',
            tanggalpr: '12 November 2022',
            tanggalpengumpulan: '19 November 2022',
            tanggalevaluasi: '20 November 2022',
            status: 'Selesai',
            skor: '90'
          },
          {
            key: '2',
            subjek: 'IPA',
            judul: 'Anatomi Manusia',
            tanggalpr: '12 November 2022',
            tanggalpengumpulan: '19 November 2022',
            tanggalevaluasi: '20 November 2022',
            status: 'Belum selesai',
            skor: ''
          },
        ],

        columns: [
          {
            title: 'Subjek',
            dataIndex: 'subjek',
            key: 'subjek',
          },
          {
            title: 'Judul',
            dataIndex: 'judul',
            key: 'judul',
          },
          {
            title: 'Tanggal PR',
            dataIndex: 'tanggalpr',
            key: 'tanggalpr',
          },
          {
            title: 'Tanggal Pengumpulan',
            dataIndex: 'tanggalpengumpulan',
            key: 'tanggalpengumpulan',
          },
          {
            title: 'Tanggal Evaluasi',
            dataIndex: 'tanggalevaluasi',
            key: 'tanggalevaluasi',
          },
          {
            title: 'Status',
            dataIndex: 'status',
            key: 'status',
          },
          {
            title: 'Skor',
            dataIndex: 'skor',
            key: 'skor',
          },
        ],
      };
    },
  };
</script>
