<template>
  <a-result
    status="404"
    title="404"
    sub-title="Sorry, the page you visited does not exist."
  >
    <template #extra>
      <a-button type="primary"
        ><router-link :to="`/${role}`">Back Home</router-link></a-button
      >
    </template>
  </a-result>
</template>
<script>
export default {
  computed: {
    role() {
      return this.$store.state.userData.roles[0].display_name
    }
  }
}
</script>