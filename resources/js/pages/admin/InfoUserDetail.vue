<template>
    <profile-component
        :user-id="$route.params.id"
        :user-data="userData"
        :editMode="true"
    />
</template>
<script>
import ProfileComponent from '../../component/ProfileComponent'
export default {
    components: { ProfileComponent },
    data() {
        return {}
    },
    computed: {
        userData() {
            const user = this.$store.state.userData
            return user ? user : null
        }
    }
}
</script>
