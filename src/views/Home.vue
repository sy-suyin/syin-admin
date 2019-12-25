<template>
  <div class="home">
    <!-- <HelloWorld msg="Welcome to Your Vue.js App"/> -->

	<lyaout></lyaout>
  </div>
</template>

<script>
// @ is an alias to /src
import Lyaout from '@/components/layout/base-layout.vue'

export default {
  name: 'home',
  components: {
    HelloWorld
  }
}
</script>
