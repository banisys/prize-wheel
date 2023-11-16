<script>
import axios from 'axios';

// import p_10 from './Components/p_10.vue'

export default {
  components: {
    // p_10
  },
  data: () => ({
    mobile: null,
    baseURL: '',
    assetsURL: '',
  }),
  computed: {

  },
  methods: {
    submit() {

      axios.defaults.withCredentials = true

      axios.get('/sanctum/csrf-cookie').then(response => {
        console.log(response);
      });

    //   axios.post(`${this.baseURL}/`).then((res) => {

    //     console.log(res);

    //   })

    }
  },
  created() {
    this.baseURL = this.$root.baseURL
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
