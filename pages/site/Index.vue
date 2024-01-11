<template>
  <div class="expiration" v-if="expiration"></div>
  <div class="container" v-else>
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
          <label for="mobile" class="form-label">توکن</label>
          <input type="text" id="mobile" class="form-control ltr" v-model="token">
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

          <h1>{{ remainTry }}</h1>
          days left to try again
          gifts list

          <button type="button" class="btn btn-danger btn-sm mt-3" v-if="flagStart" @click="start">
            شروع
          </button>

          <button type="button" class="btn btn-danger btn-sm mt-3" v-if="flagReStart" @click="reStart">
            شروع مجدد
          </button>

        </div>

      </div>

      <div class="col-6">
        <p_10 v-if="wheel.slice_num === 10 && flagWheel" :slices="wheel.slices" ref="prizeWheel" @win="submitWin" />
        <p_12 v-if="wheel.slice_num === 12 && flagWheel" :slices="wheel.slices" ref="prizeWheel" @win="submitWin" />
        <p_15 v-if="wheel.slice_num === 15 && flagWheel" :slices="wheel.slices" ref="prizeWheel" @win="submitWin" />
      </div>

    </div>
  </div>
</template>

<script>
import p_10 from './Components/p_10.vue'
import p_12 from './Components/p_12.vue'
import p_15 from './Components/p_15.vue'

export default {
  components: {
    p_10,
    p_12,
    p_15,
  },
  props: ['user', 'wheel', 'user_requirement_value_exists', 'expiration'],
  data: () => ({
    baseURL: '',
    assetsURL: '',

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

    remainTry: 0,
  }),
  computed: {

  },
  watch: {
    stepStart(newStepStart, oldStepStart) {
      newStepStart === 1 && this.fetchStepSrartData()
    }
  },
  methods: {
    submitStep1() {
      let _this = this
      axios.post(`${this.baseURL}/users/loign`, {
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
            _this.stepMobile = 0
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
      axios.post(`${this.baseURL}/users/enter_verification_code`, {
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
      axios.post(`${this.baseURL}/users/user_requirement`, {
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
      }, 1000);
    },
    submitWin(win) {
      this.flagStart = 0
      let _this = this
      axios.post(`${this.baseURL}/prizes`, win).then(res => {



      }).catch(e => {
        alert(e.response.data.message)
      })

    },
    fetchStepSrartData() {
      let _this = this
      axios.get(`${this.baseURL}/users/wheel_data/${this.wheel.slug}`).then(res => {

        _this.remainTry = res.data.data.remain_try

        if (_this.remainTry < 1) {
          _this.flagStart = 0
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
    if (this.expiration === 1) return

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
.expiration {
  height: 100vh;
  width: 100%;
  background: wheat;
  position: absolute;
  top: 0;
}
</style>
