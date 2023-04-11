import { createRouter, createWebHistory } from 'vue-router'
import Login from './pages/Login'
import Profile from './pages/Profile'
import NotFound from './pages/NotFound'
import NavbarAdmin from './layout/NavbarAdmin'
import NavbarGuru from './layout/NavbarGuru'
import NavbarSiswa from './layout/NavbarSiswa'
import NavbarPiket from './layout/NavbarPiket'

import {
    DashboardAdmin,
    InfoSiswa,
    InfoUserDetail,
    InfoGuru,
    KelolaAkun,
    KelolaKelas,
    KelolaMapel,
    LaporanHarian,
    LaporanHadir,
    LaporanHadirGuru,
    LaporanNilai,
    LaporanGuru,
    KelolaKelasDetail,
    KelolaAkunNew,
    KelolaAkunNewData
} from './pages/admin'

import {
    DashboardGuru,
    InfoSiswa as GuruInfoSiswa,
    DetailSiswa,
    LaporanHadirSiswa,
    LaporanHarianSiswa,
    LaporanIbadahGuru,
    TugasSiswa,
    JurnalKelas
} from './pages/guru'

import {
    DashboardSiswa,
    InfoKelas,
    LaporanHadir as SiswaLaporanHadir,
    LaporanHarian as SiswaLaporanHarian,
    LaporanNilai as SiswaLaporanNilai
} from './pages/siswa'

import {
    DashboardPiket,
    JurnalGuru,
    JurnalSiswa,
    KehadiranTamu,
    JanjiTemuTamu
} from './pages/piket'

const routerHistory = createWebHistory()

const router = createRouter({
    history: routerHistory,
    routes: [
        {
            path: '/',
            redirect: '/admin'
        },
        {
            path: '/login',
            component: Login,
            name: 'login'
        },
        {
            path: '/admin',
            component: NavbarAdmin,
            children: [
                {
                    path: '',
                    component: DashboardAdmin
                },
                {
                    path: 'info_siswa',
                    component: InfoSiswa
                },
                {
                    path: 'info_siswa/:id',
                    component: InfoUserDetail
                },
                {
                    path: 'info_guru',
                    component: InfoGuru
                },
                {
                    path: 'info_guru/:id',
                    component: InfoUserDetail
                },
                {
                    path: 'laporan_harian',
                    component: LaporanHarian
                },
                {
                    path: 'laporan_hadir',
                    component: LaporanHadir
                },
                {
                    path: 'laporan_hadir_guru',
                    component: LaporanHadirGuru
                },
                {
                    path: 'laporan_nilai',
                    component: LaporanNilai
                },
                {
                    path: 'laporan_guru',
                    component: LaporanGuru
                },
                {
                    path: 'kelola_akun',
                    component: KelolaAkun,
                    name: 'kelolaAkun'
                },
                {
                    path: 'kelola_akun/new',
                    component: KelolaAkunNew
                },
                {
                    path: 'kelola_akun/new/:role',
                    component: KelolaAkunNewData,
                    name: 'kelolaAkunNewData'
                },
                {
                    path: 'kelola_kelas',
                    component: KelolaKelas
                },
                {
                    path: 'kelola_kelas/:id',
                    component: KelolaKelasDetail
                },
                {
                    path: 'kelola_mapel',
                    component: KelolaMapel
                },
                {
                    path: 'profile',
                    component: Profile
                }
            ]
        },
        {
            path: '/guru',
            component: NavbarGuru,
            children: [
                {
                    path: '',
                    component: DashboardGuru
                },
                {
                    path: 'info_siswa',
                    component: GuruInfoSiswa
                },
                {
                    path: 'info_siswa/:id',
                    component: DetailSiswa
                },
                {
                    path: 'laporan_harian_siswa',
                    component: LaporanHarianSiswa
                },
                {
                    path: 'laporan_hadir_siswa',
                    component: LaporanHadirSiswa
                },
                {
                    path: 'laporan_ibadah_guru',
                    component: LaporanIbadahGuru
                },
                {
                    path: 'tugas_siswa',
                    component: TugasSiswa
                },
                {
                    path: 'profile',
                    component: Profile
                },
                {
                    path: 'jurnal_kelas',
                    component: JurnalKelas
                }
            ]
        },
        {
            path: '/siswa',
            component: NavbarSiswa,
            children: [
                {
                    path: '',
                    component: DashboardSiswa,
                    props: {
                        name: 'Andrian Putra Ramadan'
                    }
                },
                {
                    path: 'info_kelas',
                    component: InfoKelas
                },
                {
                    path: 'laporan_hadir',
                    component: SiswaLaporanHadir
                },
                {
                    path: 'laporan_harian',
                    component: SiswaLaporanHarian
                },
                {
                    path: 'laporan_nilai',
                    component: SiswaLaporanNilai
                },
                {
                    path: 'profile',
                    component: Profile
                }
            ]
        },
        {
            path: '/piket',
            component: NavbarPiket,
            children: [
                {
                    path: '',
                    component: DashboardPiket
                },
                {
                    path: 'jurnal/siswa',
                    component: JurnalSiswa
                },
                {
                    path: 'jurnal/guru',
                    component: JurnalGuru
                },
                {
                    path: 'profile',
                    component: Profile
                },
                {
                    path: 'tamu/kehadiran',
                    component: KehadiranTamu
                },
                {
                    path: 'tamu/janji-temu',
                    component: JanjiTemuTamu
                }
            ]
        },
        {
            path: '/:catchAll(.*)',
            component: NotFound
        }
    ]
})
// router.beforeEach(async(to, from) => {
//     const canAccess = await canUserAccess(to)
//     if (!canAccess) return '/login'
// })
// const canUserAccess = (to) => {
//     if(store.userData) {
//         return true
//     }
//     return false
// }
export default router
