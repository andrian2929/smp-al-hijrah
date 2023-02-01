<template>
  <a-layout>
    <a-layout-sider
      v-model:collapsed="collapsed"
      collapsible
      breakpoint="md"
      :style="{
        position: 'fixed',
        top: 0,
        bottom: 0,
        left: 0
      }"
    >
      <div class="logo" v-bind:style="[collapsed && { opacity: '0' }]">
        SMPS IT Al-Hijrah
      </div>
      <a-menu v-model:selectedKeys="selectedKeys" theme="dark" mode="inline">
        <a-sub-menu v-for="(item, index) in menu" :key="index">
          <template #title>
            <span>
              <component :is="item.icon" />
              <span>{{ item.name }}</span>
            </span>
          </template>
          <a-menu-item
            v-for="(subitem, subindex) in item.child"
            :key="`${index}${subindex}`"
          >
            <router-link :to="subitem.link">
              {{ subitem.name }}
            </router-link>
          </a-menu-item>
        </a-sub-menu>
      </a-menu>
    </a-layout-sider>
    <a-layout
      v-bind:style="[
        collapsed ? { marginLeft: '80px' } : { marginLeft: '200px' }
      ]"
    >
      <a-layout-header
        style="
          background: #fff;
          padding: 0 2rem;
          display: flex;
          justify-content: flex-end;
          align-items: center;
        "
      >
        <a-popover
          placement="bottomRight"
          v-model:visible="visible"
          trigger="click"
        >
          <template #title>
            <h5 style="margin: 5px 0">{{ userData.name }}</h5>
            <h6 style="font-weight: normal; color: #989898">
              {{ userData.role[0].name }}
            </h6>
          </template>
          <template #content>
            <div
              style="
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
                gap: 5px;
              "
            >
              <router-link :to="`/${userData.role[0].display_name}/profile`">
                <a>Profil</a>
              </router-link>
              <a @click="logout">Logout</a>
            </div>
          </template>
          <div
            style="
              display: flex;
              align-items: center;
              justify-content: center;
              gap: 5px;
              cursor: pointer;
            "
          >
            <a-avatar
              size="large"
              style="
                display: flex;
                align-items: center;
                justify-content: center;
              "
            >
              <template #icon><UserOutlined /></template>
            </a-avatar>
            <span style="font-size: 1rem">{{ userData.name }}</span>
            <down-outlined />
          </div>
        </a-popover>
      </a-layout-header>
      <a-layout-content
        style="min-height: 100vh; padding-top: 2rem; width: 100%"
      >
        <router-view :props1="andrian" />
      </a-layout-content>
    </a-layout>
  </a-layout>
</template>
<script>
export default {
  data() {
    return {
      collapsed: false,
      selectedKeys: null,
      visible: false,
      menu: [
        {
          name: 'Informasi User',
          icon: 'user-outlined',
          child: [
            {
              name: 'Informasi Siswa',
              link: '/admin/info_siswa'
            },
            {
              name: 'Informasi Guru',
              link: '/admin/info_guru'
            }
          ]
        },
        {
          name: 'Laporan',
          icon: 'file-outlined',
          child: [
            {
              name: 'Laporan Kehadiran Siswa',
              link: '/admin/laporan_hadir'
            },
            {
              name: 'Laporan Kehadiran Guru',
              link: '/admin/laporan_hadir_guru'
            },
            {
              name: 'Laporan Nilai Siswa',
              link: '/admin/laporan_nilai'
            },
            {
              name: 'Laporan Harian Siswa',
              link: '/admin/laporan_harian'
            },
            {
              name: 'Laporan Harian Guru',
              link: '/admin/laporan_guru'
            }
          ]
        },
        {
          name: 'Tata Kelola',
          icon: 'sliders-outlined',
          child: [
            {
              name: 'Manajemen Kelas',
              link: '/admin/kelola_kelas'
            },
            {
              name: 'Manajemen Mata Pelajaran',
              link: '/admin/kelola_mapel'
            },
            {
              name: 'Manajemen Akun',
              link: '/admin/kelola_akun'
            }
          ]
        }
      ]
    }
  },

  mounted() {},

  computed: {
    userData() {
      const user = this.$store.state.userData
      return user ? user : {}
    }
  },

  methods: {
    readData() {
      const vm = this
      vm.axios
        .get(vm.url('user'))
        .then((response) => {
          this.user_data.name = response.data.name
          this.user_data.role = response.data.role[0].name
        })
        .catch((error) => {
          vm.$onAjaxError(error)
        })
    },
    logout() {
      axios.get(this.url('logout')).then(() => {
        //remove localStorage
        localStorage.removeItem('loggedIn')
        localStorage.removeItem('token')

        window.location.href = '/login'
      })
    }
  }
}
</script>
<style>
.logo {
  height: 32px;
  margin: 16px;
  color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.25rem;
  overflow: hidden;
}

.site-layout .site-layout-background {
  background: #fff;
}
[data-theme='dark'] .site-layout .site-layout-background {
  background: #141414;
}
</style>
