<script>
import axios from 'axios';


// import p_10 from './Components/p_10.vue'

export default {
  components: {

  },
  data: () => ({
    code: null,
    baseURL: '',
    assetsURL: '',
  }),
  computed: {

  },
  methods: {
    submit() {

      //   axios.post(`${this.baseURL}/sellers/send_verification_code`, {
      //     mobile: this.mobile
      //   }).then((res) => {

      //     res.status === 201 ?

      //   })

    }
  },
  created() {
    this.baseURL = this.$root.baseURL + '/api/v1'
    this.assetsURL = this.$root.assetsURL
  },
  mounted() {
    console.log(this.$parent)
    console.log(this.$root)
  }
}
</script>

<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-4"></div>
      <div class="col-3">
        <label for="mobile" class="form-label">
          کد ارسالی را وارد کنید
        </label>
        <input type="text" id="mobile" class="form-control ltr" placeholder="091********" v-model="code">
      </div>
    </div>
  </div>
</template>

<style scoped></style>
