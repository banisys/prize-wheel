<template>
  <div class="container">
    <div class="row">




    </div>
  </div>
</template>


<script>


export default {
  props: ['wheel', 'prizes'],
  components: {

  },
  data: () => ({
    baseURL: '',
    assetsURL: '',
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.baseURL}/create`, {
        slice_num: this.sliceNum,
      }).then(res => {
        res.status === 201 && router.get(`wheels/${res.data.data.slug}/edit`)
      })
    }
  },
  created() {
    this.baseURL = this.$root.baseURL + '/api/v1/wheels'
    this.assetsURL = this.$root.assetsURL
  },
  mounted() {

    console.log(this.prizes);

  }
}
</script>



<style scoped></style>
