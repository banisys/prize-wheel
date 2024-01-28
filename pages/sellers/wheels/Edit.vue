<template>
  <div class="container mt-5">
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

        <div class="mt-4 form-check">
          <input type="checkbox" class="form-check-input" id="cancel_try_again" @change="changeCheckBoxDaysLeftToTryAgain"
            :checked="!flagDaysLeftToTryAgain">
          <label class="form-check-label" for="cancel_try_again">بازی مجدد نداشته باشد</label>
        </div>

        <Transition>
          <div class="mt-4" v-if="flagDaysLeftToTryAgain">
            <label for="try_again" class="form-label">بازی مجدد بعد از (روز)</label>
            <input type="number" min="1" max="365" class="form-control" id="try_again"
              v-model="form.days_left_to_try_again">
          </div>
        </Transition>






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
    </div>

    <div class="row mt-5">
      <div class="col-3">
        افزودن فایل اکسل کد های تخفیف برای
      </div>
      <div class="col-4">
        <div class="form-check">
          <select class="form-select" v-model="discountCodeSliceId">
            <option :value="null" disabled hidden>انتخاب کنید...</option>
            <option v-for="item in form.slices" :value="item.id">
              {{ item.title }}
            </option>
          </select>
        </div>
      </div>

      <div class="col-4">
        <input type="file" class="form-control" @change="discountCodeFileChanged($event)">
      </div>

      <div class="col-1">
        <button type="button" class="btn btn-success btn-sm" @click="submitDiscountCode">
          افزودن
        </button>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-4">
        عنوان
      </div>

      <div class="col-1">
        اولویت
      </div>

      <div class="col-4">
        توضیحات
      </div>

      <div class="col-1">
        موجودی
      </div>

      <div class="col-2">

      </div>

    </div>

    <div class="row mt-3" v-for="item in form.slices">
      <div class="col-4">
        <input type="text" class="form-control" v-model="item.title">
      </div>

      <div class="col-1">
        <input type="number" class="form-control" v-model="item.priority">
      </div>

      <div class="col-4">
        <textarea type="number" class="form-control" rows="1" v-model="item.description">
            </textarea>
      </div>

      <div class="col-2">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#discountCodeModal"
          v-if="item.discount_codes_count" @click="fetchDiscountCodes(item)">
          کد های تخفیف
          ({{ item.discount_codes_count }})
        </button>
        <input v-else type="number" class="form-control" v-model="item.inventory">
      </div>

      <div class="col-2">

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


  <div class="modal fade" id="discountCodeModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <h1 class="modal-title fs-5">{{ sliceSelected.title }}</h1>
          <button type="button" class="btn btn-success btn-sm" @click="deleteDiscountCode">
            حذف کدهای بدون کاربر
          </button>
        </div>
        <div class="modal-body">


          <table class="table">
            <thead>
              <tr>
                <th style="width: 10%">ردیف</th>
                <th style="width: 10%">کد</th>
                <th style="width: 10%">کاربر</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in discountCodes">
                <th>{{ ((page - 1) * per_page) + (index + 1) }}</th>
                <td>{{ item.code }}</td>
                <td>{{ item.user?.mobile }}</td>
              </tr>
            </tbody>
          </table>


        </div>
      </div>
    </div>
  </div>
</template>

<script>
import p_10 from '../Components/p_10.vue'
import p_12 from '../Components/p_12.vue'
import p_15 from '../Components/p_15.vue'
import DatePicker from 'vue3-persian-datetime-picker'

export default {
  props: ['wheel', 'userRequirements'],
  components: {
    p_10,
    p_12,
    p_15,
    DatePicker
  },
  data: () => ({
    flag: 1,
    baseURL: '',
    assetsURL: '',
    userRequirementsSelected: [],
    holderDaysLeftToTryAgain: null,
    flagDaysLeftToTryAgain: true,
    flagStartAt: true,
    flagEndAt: true,
    form: {
      title: '',
      try: null,
      days_left_to_try_again: null,
      start_at: '',
      end_at: '',
      login_method: null,
      slices: [],
      user_requirements: []
    },
    discountCodeSliceId: null,
    discountCodeFile: null,
    discountCodes: [],
    sliceSelected: {},

    page: 1,
    per_page: null,

    holderStartAt: null,
    holderEndAt: null,
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

      axios.put(`${this.baseURL}/wheels/${this.wheel.slug}`,
        {
          ...this.form,
          start_at: this.form.start_at && `${startJalali} ${startAtHour}`,
          end_at: this.form.end_at && `${endJalali} ${endAtHour}`
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
    changeCheckBoxStartAt(e) {
      this.flagStartAt = !e.target.checked
      this.form.start_at = e.target.checked ? null : this.holderStartAt
    },
    changeCheckBoxEndAt(e) {
      this.flagEndAt = !e.target.checked
      this.form.end_at = e.target.checked ? null : this.holderEndAt
    },
    discountCodeFileChanged(e) {
      const target = e.target
      this.discountCodeFile = (target && target.files) && target.files[0]
    },
    submitDiscountCode() {
      const config = {
        headers: { 'content-type': 'multipart/form-data' },
      }

      let formData = new FormData

      formData.append('wheel_id', this.wheel.id)
      formData.append('slice_id', this.discountCodeSliceId)
      formData.append('file', this.discountCodeFile)

      let _this = this

      axios.post(`${this.baseURL}/discount_codes`, formData, config).then(res => {

        _this.form.slices = res.data.data.slices

      }).catch(e => {
        alert(e.response.data.message)
      })

    },
    fetchDiscountCodes(slice) {

      this.sliceSelected = slice

      let _this = this

      axios.get(`${this.baseURL}/discount_codes/${slice.id}`
      ).then(res => {

        _this.discountCodes = res.data.data.discount_codes.data

      }).catch(e => {
        alert(e.response.data.message)
      })
    },
    deleteDiscountCode() {
      let _this = this
      axios.delete(`${this.baseURL}/discount_codes/${this.sliceSelected.id}`
      ).then(res => {

        _this.discountCodes = res.data.data.discount_codes.data

        if (!_this.discountCodes.length) {


          _this.form.slices.forEach((slice, index) => {

            if (slice.id === _this.sliceSelected.id) {

              _this.form.slices[index].discount_codes_count = 0
            }
          })
        }

      }).catch(e => {
        alert(e.response.data.message)
      })
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
