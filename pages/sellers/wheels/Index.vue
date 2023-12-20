<script>
import p_10 from '../Components/p_10.vue'
import { router } from '@inertiajs/vue3'

export default {
  components: {
    p_10
  },
  data: () => ({
    sliceNum: 10,
    baseURL: '',
    assetsURL: '',
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.baseURL}/create`, {
        slice_num: this.sliceNum,
      }).then(res => {
        res.status === 201 && router.get(`wheels/${res.data.data.slug}/edit`)
      })
    }
  },
  created() {
    this.baseURL = this.$root.baseURL + '/api/v1/wheels'
    this.assetsURL = this.$root.assetsURL
  },
  mounted() {

  }
}

</script>

<template>
  <div>
    <select v-model="sliceNum">
      <option value="10">10</option>
      <option value="12">12</option>
      <option value="15">15</option>
    </select>
    <button @click="submit">ایجاد</button>
  </div>
</template>

<style scoped></style>
