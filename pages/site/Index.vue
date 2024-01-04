<template>
  <!-- <p_10 v-if="flag" /> -->
  <!-- <button @click="reset">reset</button> -->

  <div class="container">
    <div class="row">
      <div class="col-6">

        <div v-if="stepToken">
          <label for="mobile" class="form-label">شماره موبایل</label>
          <input type="text" id="mobile" class="form-control ltr" placeholder="091********" v-model="mobile">
          <label for="mobile" class="form-label">توکن</label>
          <input type="text" id="mobile" class="form-control ltr" v-model="token">
          <button type="button" class="btn btn-danger btn-sm mt-3" @click="submitStep1">مرحله بعد</button>
        </div>

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

        <div v-if="stepUserRequirement">

          <template v-if="checkExistUserRequirement('name')">
            <label for="mobile" class="form-label">نام و نام خانوادگی</label>
            <input type="text" id="mobile" class="form-control ltr" v-model="userRequirement.name">
          </template>

          <template v-if="checkExistUserRequirement('gendor')">
            <select class="form-select" v-model="userRequirement.gender">
              <option value="1">مرد</option>
              <option value="2">زن</option>
            </select>
          </template>

          <template v-if="checkExistUserRequirement('email')">
            <label for="mobile" class="form-label">ایمیل</label>
            <input type="text" id="mobile" class="form-control ltr" v-model="userRequirement.email">
          </template>

        </div>

        <div v-if="stepStart">
          number of try
          days left to try again
          gifts list
          <button type="button" class="btn btn-danger btn-sm mt-3">شروع</button>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import p_10 from './Components/p_10.vue'

export default {
  components: {
    p_10
  },
  props: ['wheel'],
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
    }
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
    submitStep1() {
      let _this = this
      axios.post(`${this.baseURL}/step_one_loign`, {
        login_method: this.wheel.login_method,
        mobile: this.mobile,
        token: this.token
      }).then(res => {
        if (_this.wheel.login_method === 1) {

          if (res.status === 200) {
            _this.stepMobile = 0
            if (!res.data.data.user.userRequirementValues) {
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
            if (!res.data.data.user.userRequirementValues) {
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
      axios.post(`${this.baseURL}/enter_verification_code`, {
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
    }
  },
  created() {
    this.baseURL = this.$root.baseURL + '/api/v1/users'
    this.assetsURL = this.$root.assetsURL
  },
  mounted() {
    console.log(this.wheel)
    if (this.wheel.login_method === 1 || this.wheel.login_method === 2) {
      this.stepMobile = 1
    } else {
      this.stepToken = 1
    }
  }
}
</script>


<style scoped></style>
