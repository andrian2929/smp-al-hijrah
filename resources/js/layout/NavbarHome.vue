<template>
    <a-menu v-model:selectedKeys="current" mode="horizontal">
      <a-menu-item key="mail">
        <template #icon>
        </template>
        Halaman Login
      </a-menu-item>
    </a-menu>
    <slot />
  </template>
  <script>
  import { defineComponent, ref } from 'vue';
  import { MailOutlined, AppstoreOutlined, SettingOutlined } from '@ant-design/icons-vue';
  export default defineComponent({
    components: {
      MailOutlined,
      AppstoreOutlined,
      SettingOutlined,
    },
  
    setup() {
      const current = ref(['mail']);
      return {
        current,
      };
    },
  
  });
  </script>