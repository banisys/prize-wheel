<template>
  <div class="container">
    <div class="row">

      <div v-for="item in wheels" class="col-3">
        <p>{{ item.title }}</p>
        <p>
          <Link :href="`wheels/${item.slug}/edit`">ویرایش</Link>
        </p>
        <p>
          <Link :href="`wheels/${item.slug}`">نتایج بازی ها</Link>
        </p>
      </div>

      <div class="col-3">
        <p>ایجاد گردونه</p>
        <select v-model="sliceNum">
          <option value="10">10</option>
          <option value="12">12</option>
          <option value="15">15</option>
        </select>
        <button @click="submit">ایجاد</button>
      </div>

    </div>
  </div>
</template>


<script>
import p_10 from '../Components/p_10.vue'
import { router, Link } from '@inertiajs/vue3'

export default {
  props: ['wheels'],
  components: {
    p_10,
    Link
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
      axios.post(`${this.baseURL}`, {
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

    // console.log(this.wheels);

  }
}
</script>



<style scoped></style>
