<template>
  <a-row  type="flex" justify="center">
    <a-col :xs="23">
        <a-card :loading="loading" title="DASHBOARD" style="width: 100%">
        </a-card>
    </a-col>
  </a-row>

<div v-if="siswa" style="background-color: #ececec; padding: 20px">
    <a-row :gutter="16">
      <a-col :sm="24" :xl="8" :md="12" :lg="8">
       
          <a-card >
            <a-skeleton :loading="loading">
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
              </a-skeleton>
        </a-card>

        
      </a-col>

      <a-col :sm="24" :lg="16">
        <a-card :title="`Mata Pelajaran Hari Ini`">
         <a-skeleton :loading="loading">
          <a-table :dataSource="dataSource(thisDay)" :columns="columns" :pagination="false"/>
         </a-skeleton>
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


import axios from 'axios';
import moment from 'moment';
import 'moment/locale/id';


  export default defineComponent({
    components: {
    StarFilled,
    },
    data() {
      return {

        columns: [
          {
            title: 'Nama',
            dataIndex: 'matapelajaran',
            key: 'matapelajaran',
          },
    
          {
            title: 'Guru',
            dataIndex: 'guru',
            key: 'guru',
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

created(){
this.readData()
},

computed : {
thisDay(){
  return moment().format('dddd');
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
                        req : 'get_siswa_by_user_id'
                    }
                })
                .then((response) => {

                   vm.siswa = response.data.models
                   vm.siswa.created_at = moment(vm.siswa.created_at).format('DD MMMM YYYY')
                   vm.siswa.created_at_humanize = moment(vm.siswa.created_at).startOf('day').fromNow();
                   console.log(vm.siswa);

                   vm.axios.get(vm.url('kelas/read'), { params : { kelas_id: response.data.models.kelas.id, req: 'get_roster' }}).then((response) => {
                  vm.siswa = { ...vm.siswa, roster: response.data.models }
                  console.log(vm.siswa);
                  vm.loading = false
                  }).catch((e) => vm.$onAjaxError(e))
                })
                .catch((e) => vm.$onAjaxError(e))       
                
                })
                .catch((error) => {
                    vm.$onAjaxError(error)
                })
        },
        dataSource(hari){
          let roster = this.siswa.roster.filter((item) => item.hari == hari.toLowerCase())
          roster.sort((a, b) => a.waktu.localeCompare(b.waktu))
         return roster.map((item, index) => {
          return {
            key : index + 1,
            matapelajaran : item.mapel.name,
            guru: item.guru.name,
            waktu : `${item.waktu.substring(0,5)}`
          }
          })
        
        }
},

  });
</script>

