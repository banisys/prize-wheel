<template>
  <div class="container my-5">
    <div class="row">
      <div class="col-12">

        <div class="mt-3">
          <label for="title" class="form-label">عنوان</label>
          <input type="text" class="form-control" id="title" v-model="form.title">
        </div>

        <div class="mt-4">
          <label for="try" class="form-label">تعداد فرصت بازی</label>
          <input type="number" min="1" max="5" class="form-control" id="try" v-model="form.try">
        </div>



        <div class="mt-4 border p-3 rounded">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="try_check" @change="changeCheckBoxDaysLeftToTryAgain"
              :checked="!flagDaysLeftToTryAgain">
            <label class="form-check-label" for="try_check">بازی مجدد نداشته باشد</label>
          </div>

          <Transition>
            <div class="mt-4" v-if="flagDaysLeftToTryAgain">
              <label for="days_left_to_try_again" class="form-label">بازی مجدد بعد از (روز)</label>
              <input type="number" min="1" max="365" class="form-control" id="days_left_to_try_again"
                v-model="form.days_left_to_try_again">
            </div>
          </Transition>
        </div>



        <div class="mt-4 border p-3 rounded">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="try_share" @change="changeCheckBoxTryShare"
              :checked="!flagTryShare">
            <label class="form-check-label" for="try_share">جایزه اشتراک گذاری نداشته باشد</label>
          </div>

          <Transition>
            <div class="mt-4" v-if="flagTryShare">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="try_share" id="try_share_1_1" :value="1" checked
                  v-model="form.try_share">
                <label class="form-check-label" for="try_share_1_1">
                  ۱ فرصت به ازای ۱ زیر مجموعه
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="try_share" id="try_share_2_1" :value="2"
                  v-model="form.try_share">
                <label class="form-check-label" for="try_share_2_1">
                  ۲ فرصت به ازای ۱ زیر مجموعه
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="try_share" id="try_share_1_2" :value="0.5"
                  v-model="form.try_share">
                <label class="form-check-label" for="try_share_1_2">
                  ۱ فرصت به ازای ۲ زیر مجموعه
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="try_share" id="try_share_1_3" :value="0.33"
                  v-model="form.try_share">
                <label class="form-check-label" for="try_share_1_3">
                  ۱ فرصت به ازای ۳ زیر مجموعه
                </label>
              </div>
            </div>
          </Transition>
        </div>


        <div class="mt-4 border p-3 rounded">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="start" @change="changeCheckBoxStartAt"
              :checked="!flagStartAt">
            <label class="form-check-label" for="start">تاریخ شروع نداشته باشد</label>
          </div>

          <Transition>
            <div class="mt-4 form-check p-0" v-if="flagStartAt">
              <date-picker v-model="form.start_at" type="datetime" label="تاریخ شروع"></date-picker>
            </div>
          </Transition>
        </div>

        <div class="mt-4 border p-3 rounded">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="end" @change="changeCheckBoxEndAt" :checked="!flagEndAt">
            <label class="form-check-label" for="end">تاریخ انقضا نداشته باشد</label>
          </div>

          <Transition>
            <div class="mt-4 form-check p-0" v-if="flagEndAt">
              <date-picker v-model="form.end_at" type="datetime" label="تاریخ انقضا"></date-picker>
            </div>
          </Transition>
        </div>

        <div class="p-3 border mt-4 rounded">
          <p class="fw-bold">روش ورود کاربر</p>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="login_method" id="login_mobile" :value="1" checked
              v-model="form.login_method">
            <label class="form-check-label" for="login_mobile">
              موبایل
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="login_method" id="login_mobile_sms" :value="2"
              v-model="form.login_method">
            <label class="form-check-label" for="login_mobile_sms">
              موبایل با احراز هویت پیامکی
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="login_method" id="login_token" :value="3"
              v-model="form.login_method">
            <label class="form-check-label" for="login_token">
              توکن
            </label>
          </div>


          <Link :href="`${$root.baseUrl}/tokens/${wheel.slug}`" v-if="form.login_method === 3">
          مدیریت توکن ها
          </Link>


        </div>

        <div class="p-3 border mt-4 rounded">
          <p class="fw-bold">اطلاعات دریافتی از کاربر:</p>
          <div class="mt-4 form-check" v-for="  item   in   userRequirements  ">

            <input type="checkbox" class="form-check-input" :id="item.name"
              @change="changeUserRequirements($event, item.id)" :checked="userRequirementsSelected.includes(item.id)">
            <label class="form-check-label" :for="item.name">{{ item.title }}</label>

          </div>
        </div>

      </div>
    </div>

    <div class="row mt-5">
      <div class="col-5">
        <p_10 v-if="wheel.slice_num === 10" :slices="wheel.slices" />
        <p_12 v-if="wheel.slice_num === 12" :slices="wheel.slices" />
        <p_15 v-if="wheel.slice_num === 15" :slices="wheel.slices" />
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-12">
        <button type="button" class="btn btn-success w-100 btn-sm mt-3" @click="submit">
          ثبت
        </button>
      </div>
    </div>

  </div>
</template>

<script>
import p_10 from '../components/P_10.vue'
import p_12 from '../components/P_12.vue'
import p_15 from '../components/P_15.vue'
import DatePicker from 'vue3-persian-datetime-picker'
import { Link, router } from '@inertiajs/vue3'

export default {
  props: ['wheel', 'userRequirements'],
  components: {
    p_10,
    p_12,
    p_15,
    DatePicker,
    Link
  },
  data: () => ({
    userRequirementsSelected: [],
    holderDaysLeftToTryAgain: null,
    flagDaysLeftToTryAgain: true,
    flagTryShare: true,
    flagStartAt: true,
    flagEndAt: true,

    form: {
      title: '',
      try: null,
      try_share: null,
      days_left_to_try_again: null,
      start_at: '',
      end_at: '',
      login_method: null,
      user_requirements: []
    },
    discountCodeSliceId: null,
    discountCodeFile: null,

    holderStartAt: null,
    holderEndAt: null,
    holderTryShare: 1,
  }),
  computed: {
  },
  methods: {
    submit(agent = 'edit', sliceId = null) {
      let startAtDate = null
      let startAtHour = null
      let startJalali = null

      if (this.form.start_at) {
        startAtDate = this.form.start_at.split(" ")[0].split('/')
        startAtHour = this.form.start_at.split(" ")[1]

        startJalali = this.jalaliToGregorian(
          parseInt(startAtDate[0]),
          parseInt(startAtDate[1]),
          parseInt(startAtDate[2])
        ).join('-')
      }

      let endAtDate = null
      let endAtHour = null
      let endJalali = null

      if (this.form.end_at) {
        endAtDate = this.form.end_at.split(" ")[0].split('/')
        endAtHour = this.form.end_at.split(" ")[1]

        endJalali = this.jalaliToGregorian(
          parseInt(endAtDate[0]),
          parseInt(endAtDate[1]),
          parseInt(endAtDate[2])
        ).join('-')
      }

      axios.put(`${this.$root.apiUrl}/wheels/${this.wheel.slug}`, {
        ...this.form,
        start_at: this.form.start_at && `${startJalali} ${startAtHour}`,
        end_at: this.form.end_at && `${endJalali} ${endAtHour}`
      }
      ).then(res => {

        if (agent === 'slice')
          router.get(`${this.$root.baseUrl}/slices/${sliceId}/edit`)

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
    changeCheckBoxTryShare(e) {
      this.flagTryShare = !e.target.checked
      this.form.try_share = e.target.checked ? null : this.holderTryShare
    },
    changeCheckBoxDaysLeftToTryAgain(e) {
      this.flagDaysLeftToTryAgain = !e.target.checked
      this.form.days_left_to_try_again = e.target.checked ? null : this.holderDaysLeftToTryAgain
    },
    changeCheckBoxStartAt(e) {
      this.flagStartAt = !e.target.checked
      this.form.start_at = e.target.checked ? null : this.holderStartAt
    },
    changeCheckBoxEndAt(e) {
      this.flagEndAt = !e.target.checked
      this.form.end_at = e.target.checked ? null : this.holderEndAt
    }
  },
  mounted() {
    this.form = { ...this.wheel }

    const wheelUserRequirements = this.wheel.user_requirements

    this.form.user_requirements = []
    wheelUserRequirements.forEach(userRequirement => {
      this.userRequirementsSelected.push(userRequirement.id)
      this.form.user_requirements.push(userRequirement.id)
    })

    if (this.form.start_at) {
      let startAtDate = null
      let startAtHour = null
      let startGregorian = null

      startAtDate = this.form.start_at.split(" ")[0].split('-')
      startAtHour = this.form.start_at.split(" ")[1]

      startGregorian = this.gregorianToJalali(
        parseInt(startAtDate[0]),
        parseInt(startAtDate[1]),
        parseInt(startAtDate[2])
      ).join('/')

      this.form.start_at = `${startGregorian} ${startAtHour}`
    }

    if (this.form.end_at) {
      let endAtDate = null
      let endAtHour = null
      let endGregorian = null

      endAtDate = this.form.end_at.split(" ")[0].split('-')
      endAtHour = this.form.end_at.split(" ")[1]

      endGregorian = this.gregorianToJalali(
        parseInt(endAtDate[0]),
        parseInt(endAtDate[1]),
        parseInt(endAtDate[2])
      ).join('/')

      this.form.end_at = `${endGregorian} ${endAtHour}`
    }

    this.holderDaysLeftToTryAgain = this.form.days_left_to_try_again
    this.holderEndAt = this.form.end_at
    this.holderStartAt = this.form.start_at

    this.flagTryShare = this.form.try_share ? true : false
    this.flagDaysLeftToTryAgain = this.form.days_left_to_try_again ? true : false
    this.flagEndAt = this.form.end_at ? true : false
    this.flagStartAt = this.form.start_at ? true : false

  }
}
</script>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
