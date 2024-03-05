<template>
    <div class="container mt-5">
      <div class="row">
        <div class="col-4"></div>
        <div class="col-3">
          <label for="password" class="form-label">
            کلمه عبور
          </label>
          <input type="password" id="password" class="form-control ltr" v-model="password">

          <label for="passwordConfirmation" class="form-label mt-3">
            تکرار کلمه عبور
          </label>
          <input type="password" id="passwordConfirmation" class="form-control ltr" v-model="passwordConfirmation">

          <button type="button" class="btn btn-danger btn-sm mt-3" @click="submit">
            ادامه
          </button>
        </div>
      </div>
    </div>
  </template>

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
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.$root.apiUrl}/sellers/password`, {
        password: this.password,
        password_confirmation: this.passwordConfirmation,
      }).then((res) => {
        res.status === 201 && router.get('dashboard')
      })
    }
  },
  created() {

  },
  mounted() {
    // !this.seller && router.get('login')
  }
}
</script>

<style scoped></style>
