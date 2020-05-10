<template>
  <li class="flex items-center justify-center mt-4">
    <p class="ml-3 text-base font-medium leading-6 text-white whitespace-no-wrap">{{text}}</p>
  </li>
</template>

<script>
export default {
  props: {
    text: String
  }
};
</script>

<style lang="scss" scoped>
</style>