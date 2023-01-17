<template>
    <router-view />
</template>

<script>
export default {
    mounted() {
        this.readUserData()
    },
    methods: {
        readUserData() {
            const vm = this
            vm.axios
                .get(vm.url('user'))
                .then((response) => {
                    vm.$store.commit('setUserData', response.data)
                })
                .catch((error) => {
                    vm.$router.push({ name: 'login' })
                })
        }
    }
}
</script>
