<script>
import axios from 'axios';
import { router } from '@inertiajs/vue3'

export default {
  components: {

  },
  props: {
    seller: Object,
  },
  data: () => ({
    password: '',
    passwordConfirmation: '',
    baseURL: '',
    assetsURL: '',
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.baseURL}/login`, {
        mobile: this.$parent.mobile,
        password: this.password,
      }).then(res => {
        res.status === 200 && router.get('dashboard')
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
    !this.$parent.mobile && router.get('login')
  }
}
</script>

<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-4"></div>
      <div class="col-3">
        <label for="password" class="form-label">
          کلمه عبور
        </label>
        <input type="text" id="password" class="form-control ltr" v-model="password">

        <button type="button" class="btn btn-danger btn-sm mt-3" @click="submit">
          ادامه
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
