<script>
import axios from 'axios';
import { router } from '@inertiajs/vue3'

export default {
  components: {

  },
  data: () => ({
    mobile: null,
    code: null,
    baseURL: '',
    assetsURL: '',
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.baseURL}/sellers/enter_verification_code`, {
        mobile: this.mobile,
        code: this.code,
      }).then((res) => {
        // check set password
        if (res.status === 200) {
          res.data.message === 'password set' && router.get('dashboard')
          res.data.message === 'password not set' && router.get('set_password')
        }

      })
    }
  },
  created() {
    this.baseURL = this.$root.baseURL + '/api/v1'
    this.assetsURL = this.$root.assetsURL
  },
  mounted() {

  }
}
</script>

<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-4"></div>
      <div class="col-3">
        <label for="code" class="form-label">
          کد ارسالی را وارد کنید
        </label>
        <input type="text" id="code" class="form-control ltr" v-model="code">
      </div>
    </div>
  </div>
</template>

<style scoped></style>
