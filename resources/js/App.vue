<template>
  <div>This is in app.vue</div>
</template>

<script>
export default {
  mounted() {
    this.$app.init();
  },
};
</script>