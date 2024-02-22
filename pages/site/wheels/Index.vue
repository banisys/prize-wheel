<template>
  <div v-if="wheel.status === 0">
    <h1 class="text-center mt-5">
      گردونه موقتا غیر فعال است
    </h1>
  </div>

  <div class="text-center mt-5" v-else-if="not_started">
    <h1>شماره معکوس تا شروع：</h1>
    <h1 class="mt-5">
      <vue-countdown :transform="transformSlotProps" :time="start_at" v-slot="{ days, hours, minutes, seconds }">
        <div style="direction: ltr;">
          <div style="display: block;">
            روز
            <Flip :value="days" />
          </div>
          <div class="mt-4">
            <Flip :value="hours" /> :
            <Flip :value="minutes" /> :
            <Flip :value="seconds" />
          </div>
        </div>
      </vue-countdown>
    </h1>
  </div>

  <div v-else-if="finished">
    <h1 class="text-center mt-5">
      گردونه در تاریخ
      {{ convertToJalali(wheel.end_at) }}
      به پایان رسیده است
    </h1>
  </div>

  <div class="container mt-5" v-else>
    <div class="row">
      <div class="col-6">

        <div v-if="stepMobile">
          <label for="mobile" class="form-label">شماره موبایل</label>
          <input type="text" id="mobile" class="form-control ltr" placeholder="091********" v-model="mobile">
          <button type="button" class="btn btn-danger btn-sm mt-3" @click="submitStep1">مرحله بعد</button>
        </div>

        <div v-if="stepCode">
          <label for="mobile" class="form-label">کد ارسالی را وارد کنید</label>
          <input type="text" id="mobile" class="form-control ltr" v-model="code">
          <button type="button" class="btn btn-danger btn-sm mt-3" @click="submitStep2">مرحله بعد</button>
        </div>

        <div v-if="stepToken">
          <label for="mobile" class="form-label">شماره موبایل</label>
          <input type="text" id="mobile" class="form-control ltr" placeholder="091********" v-model="mobile">

          <label for="token" class="form-label mt-4">توکن</label>
          <input type="text" id="token" class="form-control ltr" v-model="token">

          <button type="button" class="btn btn-danger btn-sm mt-3" @click="submitStep1">مرحله بعد</button>
        </div>

        <div v-if="stepUserRequirement">

          <template v-if="checkExistUserRequirement('name')">
            <label for="mobile" class="form-label mt-3">نام و نام خانوادگی</label>
            <input type="text" id="mobile" class="form-control" v-model="userRequirement.name">
          </template>

          <template v-if="checkExistUserRequirement('gender')">
            <label for="mobile" class="form-label mt-3">جنسیت</label>
            <select class="form-select" v-model="userRequirement.gender">
              <option :value="null" disabled hidden>انتخاب کنید...</option>
              <option :value="1">مرد</option>
              <option :value="2">زن</option>
            </select>
          </template>

          <template v-if="checkExistUserRequirement('email')">
            <label for="mobile" class="form-label mt-3">ایمیل</label>
            <input type="text" id="mobile" class="form-control ltr" v-model="userRequirement.email">
          </template>
          <button type="button" class="btn btn-danger btn-sm mt-3" @click="submitUserRequirement">مرحله
            بعد</button>
        </div>

        <div v-if="stepStart">

          <h5 v-if="remainTry && flagRemainTry">تعداد فرصت بازی: {{ remainTry }}</h5>

          <h2 v-if="wheel.date_left_to_try_again">{{ convertToJalali(wheel.date_left_to_try_again.date_at) }}</h2>

          <ul>
            <li v-for="item in prizes">
              <p>{{ item.title }}</p>
              <p>{{ item.description }}</p>
              <p class="ltr">{{ convertToJalali(item.created_at, true) }}</p>
            </li>
          </ul>

          <button type="button" class="btn btn-danger btn-sm mt-3" v-if="flagStart" @click="start">
            شروع
          </button>

          <button type="button" class="btn btn-danger btn-sm mt-3" v-if="flagReStart" @click="reStart">
            راه اندازی مجدد
          </button>auth()->login($user)

        </div>

      </div>

      <div class="col-6">
        <Transition>
          <p_10 v-if="wheel.slice_num === 10 && flagWheel" :slices="wheel.slices" ref="prizeWheel" @win="submitWin" />
        </Transition>

        <Transition>
          <p_12 v-if="wheel.slice_num === 12 && flagWheel" :slices="wheel.slices" ref="prizeWheel" @win="submitWin" />
        </Transition>

        <Transition>
          <p_15 v-if="wheel.slice_num === 15 && flagWheel" :slices="wheel.slices" ref="prizeWheel" @win="submitWin" />
        </Transition>
      </div>

    </div>
  </div>
</template>

<script>
import VueCountdown from '@chenfengyuan/vue-countdown'
import p_10 from './components/P_10.vue'
import p_12 from './components/P_12.vue'
import p_15 from './components/P_15.vue'
import Flip from "./components/Flip.vue"

export default {
  components: {
    p_10,
    p_12,
    p_15,
    VueCountdown,
    Flip
  },
  props: ['user', 'wheel', 'user_requirement_value_exists', 'not_started', 'finished'],
  data: () => ({
    stepToken: 0,
    stepMobile: 0,
    stepCode: 0,
    stepUserRequirement: 0,
    stepStart: 0,

    mobile: '',
    token: '',
    code: '',

    userRequirement: {
      name: '',
      gender: null,
      email: ''
    },
    flagWheel: 1,
    flagStart: 1,
    flagReStart: 0,
    flagRemainTry: 1,

    remainTry: 0,
    prizes: [],

    start_at: null
  }),
  watch: {
    stepStart(newValue, oldValue) {
      newValue === 1 && this.fetchStepStartData()
    }
  },
  computed: {

  },
  methods: {
    submitStep1() {
      let _this = this
      axios.post(`${this.$root.apiUrl}/users/loign`, {
        wheel_id: this.wheel.id,
        login_method: this.wheel.login_method,
        mobile: this.mobile,
        token: this.token
      }).then(res => {
        if (_this.wheel.login_method === 1) {

          if (res.status === 200) {
            _this.stepMobile = 0
            if (_this.wheel.user_requirements.length && !res.data.data.user_requirement_value_exists) {
              _this.stepUserRequirement = 1
            } else {
              _this.stepStart = 1
            }
          }

        } else if (_this.wheel.login_method === 2) {

          if (res.status === 201) {
            _this.stepMobile = 0
            _this.stepCode = 1
          }

        } else if (_this.wheel.login_method === 3) {

          if (res.status === 200) {
            _this.stepToken = 0
            if (!res.data.data.user_requirement_value_exists) {
              _this.stepUserRequirement = 1
            } else {
              _this.stepStart = 1
            }
          }

        }
      }).catch((e) => {
        alert(e.response.data.message)
      })
    },
    submitStep2() {
      let _this = this
      axios.post(`${this.$root.apiUrl}/users/enter_verification_code`, {
        mobile: this.mobile,
        code: this.code
      }).then(res => {

        if (res.status === 200) {
          _this.stepCode = 0
          if (!res.data.data.user.userRequirementValues) {
            _this.stepUserRequirement = 1
          } else {
            _this.stepStart = 1
          }
        }

      }).catch(e => {
        alert(e.response.data.message)
      })
    },
    checkExistUserRequirement(userRequirement) {
      let flag = false
      this.wheel.user_requirements.forEach(item => {

        if (item.name === userRequirement) {
          flag = true
        }
      })

      return flag
    },
    submitUserRequirement() {

      for (const [key, value] of Object.entries(this.userRequirement))
        !value && delete this.userRequirement[key]


      let _this = this
      axios.post(`${this.$root.apiUrl}/users/user_requirement`, {
        wheel_id: this.wheel.id,
        user_requirement: this.userRequirement
      }).then(res => {

        if (res.status === 201) {
          _this.stepUserRequirement = 0
          _this.stepStart = 1
        }

      }).catch(e => {
        alert(e.response.data.message)
      })
    },
    start() {
      this.$refs.prizeWheel.start()
    },
    reStart() {
      this.flagWheel = 0
      setTimeout(() => {
        this.flagWheel = 1
        this.flagReStart = 0
        this.flagStart = 1
      }, 500)
    },
    submitWin(win) {

      this.flagStart = 0
      this.flagReStart = 0
      this.flagRemainTry = 0
      let _this = this

      axios.post(`${this.$root.apiUrl}/prizes`, win).then(res => {

        setTimeout(() => {
          _this.remainTry = res.data.data.remain_try
          _this.prizes = res.data.data.prizes.data

          _this.flagReStart = 1

          if (_this.remainTry < 1) {
            _this.flagStart = 0
            _this.flagReStart = 0
          } else {
            _this.flagRemainTry = 1
          }
        }, 10000)


      }).catch(e => {
        alert(e.response.data.message)
      })
    },
    fetchStepStartData() {
      let _this = this
      axios.get(`${this.$root.apiUrl}/users/wheel_data/${this.wheel.slug}`).then(res => {

        _this.remainTry = res.data.data.remain_try
        _this.prizes = res.data.data.prizes.data

        if (_this.remainTry < 1) {
          _this.flagStart = 0
        }

      }).catch(e => {
        alert(e.response.data.message)
      })
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
    convertToJalali(date, hour = true) {
      if (date) {
        let atDate = null
        let atHour = null
        let atGregorian = null

        atDate = date.split(" ")[0].split('-')
        atHour = date.split(" ")[1]

        atGregorian = this.gregorianToJalali(
          parseInt(atDate[0]),
          parseInt(atDate[1]),
          parseInt(atDate[2])
        ).join('/')

        const splitHour = atHour.split(':');

        atHour = `${splitHour[0]}:${splitHour[1]}`

        return hour ? `${atGregorian} ${atHour}` : atGregorian
      }
    },
    transformSlotProps(props) {
      const formattedProps = {};

      Object.entries(props).forEach(([key, value]) => {
        formattedProps[key] = value < 10 ? `0${value}` : String(value);
      });

      return formattedProps;
    },
  },
  created() {

  },
  mounted() {
    const now = new Date()
    const start_at = new Date(this.wheel.start_at)

    this.start_at = start_at - now

    if (this.not_started || this.finished) return


    if (this.user) {
      if (this.wheel.user_requirements.length && !this.user_requirement_value_exists) {
        this.stepUserRequirement = 1
      } else {
        this.stepStart = 1
      }
      return
    }

    if (this.wheel.login_method === 1 || this.wheel.login_method === 2) {
      this.stepMobile = 1
    } else {
      this.stepToken = 1
    }
  }
}
</script>


<style scoped>
.end {
  height: 100vh;
  width: 100%;
  background: wheat;
  position: absolute;
  top: 0;
}

.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
