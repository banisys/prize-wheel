<script>
import p_10 from '../Components/p_10.vue'
import { router } from '@inertiajs/vue3'
import DatePicker from 'vue3-persian-datetime-picker'

export default {
  props: ['wheel'],
  components: {
    p_10,
    DatePicker
  },
  data: () => ({
    flag: 1,
    date: null
  }),
  computed: {

  },
  methods: {
    // reset() {
    //   this.flag = 0
    //   setTimeout(() => {
    //     this.flag = 1
    //   }, 1000);
    // }
  },
  mounted() {

    console.log(this.wheel);

  }
}

</script>

<template>
  <!-- <p_10 v-if="flag" /> -->
  <!-- <button @click="reset">reset</button> -->

  <div class="container">
    <div class="row">
      <div class="col-3">

        <div class="mt-3">
          <label for="title" class="form-label">عنوان</label>
          <input type="text" class="form-control" id="title">
        </div>

        <div class="mt-4">
          <label for="try" class="form-label">تعداد فرصت بازی</label>
          <input type="number" min="1" max="5" class="form-control" id="try">
        </div>

        <div class="mt-4">
          <label for="try_again" class="form-label">بازی مجدد بعد از (روز)</label>
          <input type="number" min="1" max="365" class="form-control" id="try_again">
        </div>

        <div class="mt-4 form-check">
          <input type="checkbox" class="form-check-input" id="cancel_try_again">
          <label class="form-check-label" for="cancel_try_again">بازی مجدد نداشته باشد</label>
        </div>

        <div class="mt-4 form-check">
          <date-picker v-model="expirationDate" label="تاریخ انقضا"></date-picker>
        </div>

        <div class="mt-4 form-check">
          <input type="checkbox" class="form-check-input" id="expiration">
          <label class="form-check-label" for="expiration">تاریخ انقضا نداشته باشد</label>
        </div>

        <div class="p-3 border mt-4 rounded">
          <p class="fw-bold">اطلاعات دریافتی از کاربر:</p>
          <div class="mt-4 form-check">
            <input type="checkbox" class="form-check-input" id="name">
            <label class="form-check-label" for="name">نام و نام خانوادگی</label>
          </div>
          <div class="mt-4 form-check">
            <input type="checkbox" class="form-check-input" id="mobile">
            <label class="form-check-label" for="mobile">موبایل</label>
          </div>
          <div class="mt-4 form-check">
            <input type="checkbox" class="form-check-input" id="gender">
            <label class="form-check-label" for="gender">جنسیت</label>
          </div>
          <div class="mt-4 form-check">
            <input type="checkbox" class="form-check-input" id="email">
            <label class="form-check-label" for="email">ایمیل</label>
          </div>
        </div>

      </div>
      <div class="col-6"></div>
    </div>
  </div>
</template>

<style scoped></style>
