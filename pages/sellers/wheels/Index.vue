<template>
  <div class="container">
    <div class="row mt-5">

      <div v-for="item in _wheels" class="col-3">
        <h6>{{ item.title }}</h6>
        <p>
          <Link :href="`wheels/${item.slug}/edit`">ویرایش</Link>
        </p>
        <p>
          <Link :href="`wheels/${item.slug}`">نتایج بازی ها</Link>
        </p>
        <p>
          <a target="_blank" :href="`/${item.slug}`">مشاهده گردونه</a>
        </p>
        <button class="btn btn-danger btn-sm" @click="deleteWheel(item.slug)">حذف</button>

        <div class="alert alert-primary mt-3" role="alert" v-if="item.login_method === 2 && item.seller.sms_number < 1">
          پیامک های خود را شارژ کنید
        </div>
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
    _wheels: [],
  }),
  computed: {

  },
  methods: {
    submitCreate() {
      axios.post(`${this.$root.apiUrl}/sellers/wheels`, {
        slice_num: this.sliceNum,
      }).then(res => {
        res.status === 201 && router.get(`wheels/${res.data.data.slug}/edit`)
      })
    },
    deleteWheel(wheelSlug) {
      let _this = this
      axios.delete(`${this.$root.apiUrl}/sellers/wheels/${wheelSlug}`).then(res => {
        const w = Object.values(JSON.parse(JSON.stringify(_this._wheels)))

        _this._wheels = res.status === 201 &&
          w.filter(wheel => wheel.slug !== wheelSlug)

      })
    }
  },
  created() {

  },
  mounted() {
    this._wheels = { ...this.wheels }
  }
}
</script>



<style scoped></style>
