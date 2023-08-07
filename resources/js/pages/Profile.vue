<template>
    <profile-component
        v-if="userData"
        :user-id="userData.id"
        :user-data="userData"
        :editMode="true"
    />
</template>
<script>
import ProfileComponent from '../component/ProfileComponent'
export default {
    components: { ProfileComponent },

    computed: {
        userData() {
            const user = this.$store.state.userData
            return user ? user : null
        }
    }
}
</script>
