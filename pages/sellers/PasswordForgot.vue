<script>
import axios from 'axios'
import { router } from '@inertiajs/vue3'

export default {
  components: {

  },
  data: () => ({
    mobile: '',
    baseURL: '',
    assetsURL: '',
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.baseURL}/send_verification_code?password_forgot=true`, {
        mobile: this.mobile
      }).then(res => {
        this.$parent.mobile = this.mobile
        res.status === 201 && router.get('code?password_forgot=true')

      }).catch((e) => {
        alert(e.response.data.message)
      })
    }
  },
  created() {
    this.baseURL = this.$root.baseURL + '/api/v1/sellers'
    this.assetsURL = this.$root.assetsURL
  },
  mounted() {

  }
}
</script>

<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-4">
      </div>
      <div class="col-3">
        <label for="mobile" class="form-label">
          شماره موبایل
        </label>
        <input type="text" id="mobile" class="form-control ltr" placeholder="091********" v-model="mobile">
        <button type="button" class="btn btn-danger btn-sm mt-3" @click="submit">
          ورود
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
