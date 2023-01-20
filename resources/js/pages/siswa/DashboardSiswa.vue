<template>
 
  <a-row  type="flex" justify="center">
    <a-col :xs="23">
        <a-card :loading="loading" title="DASHBOARD" style="width: 100%">
        </a-card>
    </a-col>
  </a-row>

<div v-if="siswa" style="background-color: #ececec; padding: 20px">
    <a-row :gutter="16">
      <a-col :span="8">
        <a-skeleton :loading="loading">
          <a-card >
            <div align="center">
                <img
                    style="width: 50%"
                    :src="siswa.user.image == 'default.png' ? '/img/no_profile.png' : '/img/profile_photo/' + siswa.user.image"
                    alt="profile picture"
                />
                <h3 style="marginTop: 30px; marginBottom: 30px">{{ siswa.name }}</h3>
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
                    <p>{{ siswa.kelas.jenjang }} - {{ siswa.kelas.section }}</p>
                  </a-col>
                  <a-col :span="12">
                    <strong>Beasiswa</strong>
                  </a-col>
                  <a-col :span="12">
                    <p>{{ siswa.is_beasiswa == 0 ? 'Tidak' : 'Ya' }}</p>
                  </a-col>
                  <a-col :span="12">
                    <strong>Tanggal Bergabung</strong>
                  </a-col>
                  <a-col :span="12">
                  <span> {{ siswa.created_at }}</span>
                  <span style="font-size: 12px; display: block;"> <i>{{ siswa.created_at_humanize }}</i> </span>
                  
                  </a-col>
                </a-row>
        </a-card>
    </a-skeleton>
        
      </a-col>

      <a-col :span="16">
        <a-card>
          <a-table :dataSource="dataSource" :columns="columns" :pagination="false"/>
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
                    <strong>Mengikuti Kegiatan di Luar</strong>
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

import { mapState } from 'vuex'

import axios from 'axios';
import moment from 'moment';
import 'moment/locale/id';


  export default defineComponent({
    components: {
    StarFilled,
    },
    data() {
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
        siswa : {
          nama : 'Madan'
        },
        loading : true

  
      };
    },
  
  mounted() {
  
  
},
created(){
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
                        req : 'get_siswa_by_user_id'
                    }
                })
                .then((response) => {

                   vm.siswa = response.data.models
                   moment.locale('id');
                   vm.siswa.created_at = moment(vm.siswa.created_at).format('DD MMMM YYYY')
                   vm.siswa.created_at_humanize = moment(vm.siswa.created_at).startOf('day').fromNow();
                   console.log(vm.siswa);
                   this.loading = false
                })
                .catch((e) => vm.$onAjaxError(e))       
                
                })
                .catch((error) => {
                    vm.$onAjaxError(error)
                })
        }
},
    

    

  });
</script>

