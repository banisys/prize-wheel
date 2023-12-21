<script>
import p_10 from '../Components/p_10.vue'
import { router } from '@inertiajs/vue3'

export default {
  props: ['wheel'],
  components: {
    p_10
  },
  data: () => ({
    flag: 1
  }),
  computed: {

  },
  methods: {
    // reset() {
    //   this.flag = 0
    //   setTimeout(() => {
    //     this.flag = 1
    //   }, 1000);
    // }
  },
  mounted() {

    console.log(this.wheel);

  }
}

</script>

<template>
  <!-- <p_10 v-if="flag" /> -->
  <!-- <button @click="reset">reset</button> -->


</template>

<style scoped></style>
