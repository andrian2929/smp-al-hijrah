<template>
<a-card :loading="loading" title="Kehadiran" style="width: 100%; margin-bottom: 20px">
  <a-calendar v-model:value="value" @panelChange="onPanelChange" />
  </a-card>
</template>
<script>
import { defineComponent, ref } from 'vue';
export default defineComponent({
  setup() {
    const value = ref();
    const onPanelChange = (value, mode) => {
      console.log(value, mode);
    };
    return {
      value,
      onPanelChange,
    };
  },
});
</script>