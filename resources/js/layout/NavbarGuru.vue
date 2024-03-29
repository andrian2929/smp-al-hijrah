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
            <a-menu
                v-model:selectedKeys="selectedKeys"
                theme="dark"
                mode="inline"
            >
                <template v-for="(item, index) in menu" :key="index">
                    <template v-if="item.child">
                        <a-sub-menu :key="index">
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
                    </template>
                    <template v-else>
                        <a-menu-item :key="index">
                            <template #icon>
                                <component :is="item.icon" />
                            </template>
                            <router-link :to="item.url">
                                {{ item.name }}
                            </router-link>
                        </a-menu-item>
                    </template>
                </template>
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
                            <router-link
                                :to="`/${userData.role[0].display_name}/profile`"
                            >
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
                                gap: 5px;
                            "
                            :src="
                                userData.image == 'default.png'
                                    ? '/img/no_profile.png'
                                    : '/img/profile_photo/' + userData.image
                            "
                            alt="profile picture"
                        >
                        </a-avatar>
                        <span style="font-size: 1rem">{{ userData.name }}</span>
                        <down-outlined />
                    </div>
                </a-popover>
            </a-layout-header>
            <a-layout-content
                style="min-height: 100vh; padding-top: 2rem; width: 100%"
            >
                <router-view />
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
                    name: 'Dashboard',
                    icon: 'dashboard-outlined',
                    url: '/guru'
                },
                {
                    name: 'Informasi Siswa',
                    icon: 'user-outlined',
                    url: '/guru/info_siswa'
                },
                {
                    name: 'Jurnal Kelas',
                    icon: 'read-outlined',
                    url: '/guru/jurnal_kelas'
                },
                {
                    name: 'Laporan',
                    icon: 'file-outlined',
                    child: [
                        {
                            name: 'Laporan Kehadiran Siswa',
                            link: '/guru/laporan_hadir_siswa'
                        },
                        {
                            name: 'Laporan Harian Siswa',
                            link: '/guru/laporan_harian_siswa'
                        },
                        {
                            name: 'Laporan Ibadah Harian',
                            link: '/guru/laporan_ibadah_guru'
                        }
                    ]
                },
                {
                    name: 'Penilaian Akademik',
                    icon: 'read-outlined',
                    url: '/guru/tugas_siswa'
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
