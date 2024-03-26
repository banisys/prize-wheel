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

        <v-btn color="primary">Button</v-btn>

      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { router } from '@inertiajs/vue3'

export default {
  components: {

  },
  data: () => ({
    mobile: '',
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.$root.apiUrl}/sellers/send_verification_code`, {
        mobile: this.mobile
      }).then(res => {
        this.$parent.mobile = this.mobile
        res.status === 201 && router.get('code')
        res.status === 200 && router.get('password')

      }).catch((e) => {
        alert(e.response.data.message)
      })
    }
  },
  created() {

  },
  mounted() {

  }
}
</script>



<style scoped></style>
