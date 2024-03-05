<script>
import axios from 'axios';
import { router } from '@inertiajs/vue3'

export default {
  components: {

  },
  data: () => ({
    code: '',
    passwordForgot: null
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.$root.apiUrl}/sellers/check_verification_code`, {
        mobile: this.$parent.mobile,
        code: this.code,
      }).then((res) => {
        if (res.status === 200) {

          if (this.passwordForgot) {
            router.get('password-register')
          } else if (res.data.message === 'password set') {
            router.get('dashboard')
          } else if (res.data.message === 'password not set') {
            router.get('password-register')
          }

        }
      }).catch((e) => {
        alert(e.response.data.message)
      })
    }
  },
  created() {
    const url = new URL(window.location.href)
    this.passwordForgot = url.searchParams.get("password_forgot")
  },
  mounted() {
    if (this.passwordForgot && !this.$parent.mobile) {
      router.get('password-forgot')
    } else if (!this.$parent.mobile) {
      router.get('login')
    }
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
        <button type="button" class="btn btn-danger btn-sm mt-3" @click="submit">
          ادامه
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
