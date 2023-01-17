<template>
    <a-row type="flex" justify="center">
        <a-col :xs="23">
            <a-card title="Tambah Akun">
                <a-card title="Tambah Manual">
                    <p>Pilih Jenis Akun</p>
                    <a-select v-model:value="role" style="width: 200px">
                        <a-select-option
                            v-for="rl in roleOptions"
                            :key="rl.id"
                            :value="rl.display_name"
                        >
                            {{ rl.name }}
                        </a-select-option>
                    </a-select>
                    <a-button type="primary" @click="newUser"
                        >Buat Akun</a-button
                    >
                </a-card>
                <a-card title="Inner card title" :style="{ marginTop: '16px' }">
                    Tambah Massal
                </a-card>
            </a-card>
        </a-col>
    </a-row>
</template>

<script>
export default {
    data() {
        return {
            role: 'Siswa',
            roleOptions: []
        }
    },
    mounted() {
        this.getAllRoles()
    },
    methods: {
        newUser() {
            this.$router.push({
                path: `/admin/kelola_akun/new/${this.role.toLowerCase()}`
            })
        },
        getAllRoles() {
            const vm = this
            const params = { req: 'roles' }
            vm.axios
                .get(vm.url('user/read'), { params: params })
                .then((response) => {
                    vm.roleOptions = response.data.models
                })
                .catch((e) => vm.$onAjaxError(e))
        }
    }
}
</script>
