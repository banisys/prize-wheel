<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-12">
        <div class="p-3 border mt-4 rounded">
          <p class="fw-bold">پلن ها</p>

          <div class="form-check" v-for="(item, index) in plans">
            <input class="form-check-input" type="radio" name="plan" :id="`plan-${index}`" :value="item.id"
              v-model="plan">
            <label class="form-check-label" :for="`plan-${index}`">
              {{ item.title }}
            </label>
          </div>

        </div>
      </div>
      <div class="col-12">
        <button type="button" class="btn btn-success w-100 btn-sm mt-3" @click="submitPlan">
          پرداخت
        </button>
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
  props: ['plans'],
  data: () => ({
    planId: null,

  }),
  computed: {

  },
  methods: {
    submitPlan() {
      axios.post(`${this.$root.apiUrl}/orders`, {
        plan_id: this.planId
      }).then(res => {

      })
    }
  },
  created() {

  },
  mounted() {
    console.log(this.plans);
  }
}
</script>

<style scoped></style>
