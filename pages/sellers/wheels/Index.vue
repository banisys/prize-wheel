<template>
  <div class="container">
    <div class="row mt-5">

      <div v-for="item in wheels" class="col-3">
        <h6>{{ item.title }}</h6>
        <p>
          <Link :href="`wheels/${item.slug}/edit`">ویرایش</Link>
        </p>
        <p>
          <Link :href="`wheels/${item.slug}`">نتایج بازی ها</Link>
        </p>
        <button class="btn btn-danger btn-sm" @click="deleteWheel(item.slug)">حذف</button>
      </div>

      <div class="col-3">
        <p>ایجاد گردونه</p>
        <select v-model="sliceNum">
          <option value="10">10</option>
          <option value="12">12</option>
          <option value="15">15</option>
        </select>
        <button @click="submitCreate">ایجاد</button>
      </div>

    </div>
  </div>
</template>


<script>
import p_10 from '../components/P_10.vue'
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
    submitCreate() {
      axios.post(`${this.baseURL}`, {
        slice_num: this.sliceNum,
      }).then(res => {
        res.status === 201 && router.get(`wheels/${res.data.data.slug}/edit`)
      })
    },
    deleteWheel(wheelSlug) {
      let _this = this
      axios.delete(`${this.baseURL}/${wheelSlug}`).then(res => {
        _this.wheels = res.status === 201 &&
          _this.wheels.filter(wheel => wheel.slug !== wheelSlug)
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
