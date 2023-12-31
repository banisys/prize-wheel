<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-3">

        <div class="mt-3">
          <label for="title" class="form-label">عنوان</label>
          <input type="text" class="form-control" id="title" v-model="form.title">
        </div>

        <div class="mt-4">
          <label for="try" class="form-label">تعداد فرصت بازی</label>
          <input type="number" min="1" max="5" class="form-control" id="try" v-model="form.try">
        </div>

        <div class="mt-4">
          <label for="try_again" class="form-label">بازی مجدد بعد از (روز)</label>
          <input type="number" min="1" max="365" class="form-control" id="try_again" :disabled="!flagDaysLeftToTryAgain"
            v-model="form.days_left_to_try_again">
        </div>

        <div class="mt-4 form-check">
          <input type="checkbox" class="form-check-input" id="cancel_try_again" @change="changeCheckBoxDaysLeftToTryAgain"
            :checked="!flagDaysLeftToTryAgain">
          <label class="form-check-label" for="cancel_try_again">بازی مجدد نداشته باشد</label>
        </div>

        <div class="mt-4 form-check p-0">
          <date-picker :disabled="!flagPeriodAt" v-model="form.period_at" type="datetime"
            label="تاریخ انقضا"></date-picker>
        </div>

        <div class="mt-4 form-check">
          <input type="checkbox" class="form-check-input" id="expiration" @change="changeCheckBoxperiodAt"
            :checked="!flagPeriodAt">
          <label class="form-check-label" for="expiration">تاریخ انقضا نداشته باشد</label>
        </div>

        <div class="p-3 border mt-4 rounded">
          <p class="fw-bold">روش ورود کاربر</p>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="login_method" id="login_mobile" value="1" checked
              v-model="form.login_method">
            <label class="form-check-label" for="login_mobile">
              موبایل
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="login_method" id="login_mobile_sms" value="2"
              v-model="form.login_method">
            <label class="form-check-label" for="login_mobile_sms">
              موبایل با احراز هویت پیامکی
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="login_method" id="login_token" v-model="form.login_method"
              value="3">
            <label class="form-check-label" for="login_token">
              توکن
            </label>
          </div>
        </div>

        <div class="p-3 border mt-4 rounded">
          <p class="fw-bold">اطلاعات دریافتی از کاربر:</p>
          <div class="mt-4 form-check" v-for="item in userRequirements">

            <input type="checkbox" class="form-check-input" :id="item.name"
              @change="changeUserRequirements($event, item.id)" :checked="userRequirementsSelected.includes(item.id)">
            <label class="form-check-label" :for="item.name">{{ item.title }}</label>

          </div>
        </div>

      </div>
      <div class="col-4">
        <div class="row mt-4" v-for="item in form.slices">
          <div class="col-10">
            <input type="text" class="form-control" id="title" v-model="item.title">
          </div>
          <div class="col-2 p-0">
            <input type="number" class="form-control col-2" id="title" v-model="item.priority">
          </div>
        </div>
      </div>

      <div class="col-5">
        <p_10 v-if="wheel.slice_num === 10" :slices="wheel.slices" />
        <p_12 v-if="wheel.slice_num === 12" :slices="wheel.slices" />
        <p_15 v-if="wheel.slice_num === 15" :slices="wheel.slices" />
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <button type="button" class="btn btn-success w-100 btn-sm mt-3" @click="submit">
          ثبت
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import p_10 from '../Components/p_10.vue'
import { router } from '@inertiajs/vue3'
import DatePicker from 'vue3-persian-datetime-picker'

export default {
  props: ['wheel', 'userRequirements'],
  components: {
    p_10,
    DatePicker
  },
  data: () => ({
    flag: 1,
    baseURL: '',
    assetsURL: '',
    userRequirementsSelected: [],
    holderDaysLeftToTryAgain: null,
    flagDaysLeftToTryAgain: true,
    flagPeriodAt: true,
    form: {
      title: '',
      try: null,
      days_left_to_try_again: null,
      period_at: null,
      login_method: null,
      slices: {},
      user_requirements: []
    }
  }),
  computed: {
  },
  methods: {
    submit() {
      let sumPriority = 0
      this.form.slices.forEach(item => sumPriority += item.priority)

      if (sumPriority !== 100) {
        alert('مجموع احتمالات باید عدد 100 شود.')
        return
      }

      let periodAtDate = null
      let periodAtHour = null
      let periodJalali = null

      if (this.form.period_at) {
        periodAtDate = this.form.period_at.split(" ")[0].split('/')
        periodAtHour = this.form.period_at.split(" ")[1]

        periodJalali = this.jalaliToGregorian(
          parseInt(periodAtDate[0]),
          parseInt(periodAtDate[1]),
          parseInt(periodAtDate[2])
        ).join('-')
      }

      axios.put(`${this.baseURL}/wheels/${this.wheel.slug}`,
        {
          ...this.form,
          period_at: this.form.period_at && `${periodJalali} ${periodAtHour}`
        }
      ).then(res => {

      }).catch(e => {
        alert(e.response.data.message)
      })
    },
    jalaliToGregorian(jy, jm, jd) {
      var sal_a, gy, gm, gd, days;
      jy += 1595;
      days = -355668 + (365 * jy) + (~~(jy / 33) * 8) + ~~(((jy % 33) + 3) / 4) + jd + ((jm < 7) ? (jm - 1) * 31 : ((jm - 7) * 30) + 186);
      gy = 400 * ~~(days / 146097);
      days %= 146097;
      if (days > 36524) {
        gy += 100 * ~~(--days / 36524);
        days %= 36524;
        if (days >= 365) days++;
      }
      gy += 4 * ~~(days / 1461);
      days %= 1461;
      if (days > 365) {
        gy += ~~((days - 1) / 365);
        days = (days - 1) % 365;
      }
      gd = days + 1;
      sal_a = [0, 31, ((gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
      for (gm = 0; gm < 13 && gd > sal_a[gm]; gm++) gd -= sal_a[gm];
      return [gy, gm, gd];
    },
    gregorianToJalali(gy, gm, gd) {
      var g_d_m, jy, jm, jd, gy2, days;
      g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
      gy2 = (gm > 2) ? (gy + 1) : gy;
      days = 355666 + (365 * gy) + ~~((gy2 + 3) / 4) - ~~((gy2 + 99) / 100) + ~~((gy2 + 399) / 400) + gd + g_d_m[gm - 1];
      jy = -1595 + (33 * ~~(days / 12053));
      days %= 12053;
      jy += 4 * ~~(days / 1461);
      days %= 1461;
      if (days > 365) {
        jy += ~~((days - 1) / 365);
        days = (days - 1) % 365;
      }
      if (days < 186) {
        jm = 1 + ~~(days / 31);
        jd = 1 + (days % 31);
      } else {
        jm = 7 + ~~((days - 186) / 30);
        jd = 1 + ((days - 186) % 30);
      }
      return [jy, jm, jd];
    },
    changeUserRequirements(e, id) {
      if (e.target.checked) {
        this.form.user_requirements.push(id)
      } else {
        const index = this.form.user_requirements.indexOf(id);
        index !== -1 && this.form.user_requirements.splice(index, 1)
      }
    },
    changeCheckBoxDaysLeftToTryAgain(e) {
      this.flagDaysLeftToTryAgain = !e.target.checked
      this.form.days_left_to_try_again = e.target.checked ? null : this.holderDaysLeftToTryAgain
    },
    changeCheckBoxperiodAt(e) {
      this.flagPeriodAt = !e.target.checked
      this.form.period_at = e.target.checked ? null : this.holderPeriodAt
    }
  },
  created() {
    this.baseURL = this.$root.baseURL + '/api/v1'
    this.assetsURL = this.$root.assetsURL
  },
  mounted() {
    this.form = this.wheel

    const wheelUserRequirements = this.wheel.user_requirements

    this.form.user_requirements = []
    wheelUserRequirements.forEach(userRequirement => {
      this.userRequirementsSelected.push(userRequirement.id)
      this.form.user_requirements.push(userRequirement.id)
    })

    if (this.form.period_at) {
      let periodAtDate = null
      let periodAtHour = null
      let periodGregorian = null

      periodAtDate = this.form.period_at.split(" ")[0].split('-')
      periodAtHour = this.form.period_at.split(" ")[1]

      periodGregorian = this.gregorianToJalali(
        parseInt(periodAtDate[0]),
        parseInt(periodAtDate[1]),
        parseInt(periodAtDate[2])
      ).join('/')

      this.form.period_at = `${periodGregorian} ${periodAtHour}`
    }

    this.holderDaysLeftToTryAgain = this.form.days_left_to_try_again
    this.holderPeriodAt = this.form.period_at

    this.flagDaysLeftToTryAgain = this.form.days_left_to_try_again ? true : false
    this.flagPeriodAt = this.form.period_at ? true : false
  }
}
</script>

<style scoped></style>
