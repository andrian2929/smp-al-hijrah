<template>
  <profile-component :user-id="$route.params.id" :editMode="true" />
</template>
<script>
import ProfileComponent from '../../component/ProfileComponent'
export default {
  components: { ProfileComponent },
  data() {
    return {}
  },
  mounted() {
    console.log(this.$route.params.id)
  }
}
</script>