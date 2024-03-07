<template>
  <div class="container">

    <div class="row mt-5">
      <div class="col-12" v-if="wheel.login_method === 2 && wheel.seller.sms_number < 1">
        <div class="alert alert-primary" role="alert">
          پیامک های خود را شارژ کنید
        </div>
      </div>
      <div class="col-6">
        <h4>{{ wheel.title }}</h4>
        <table class="table">
          <thead>
            <tr>
              <th style="width: 10%">ردیف</th>
              <th style="width: 20%">موبایل</th>
              <th style="width: 30%">
                <input type="text" class="form-control" placeholder="جستجو براساس موبایل" v-model="search.mobile"
                  @keyup.enter="submitSearch">
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in _users.data">
              <th scope="row">{{ ((page - 1) * per_page) + (index + 1) }}</th>
              <td>{{ item.mobile }}</td>
              <td>
                <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#prizeModal"
                  @click="setDataToPrizesModal(item.mobile, item.prizes)">
                  جوایز
                </button>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#userModal"
                  @click="setDataToUserModal(item.mobile, item.user_requirement_values)">
                  اطلاعات کاربر
                </button>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-6">
        <h4>مجموع برد به تفکیک هر جایزه</h4>
        <ul class="mt-4">
          <li v-for="(item, key) in sum_number_of_each_prize">
            {{ key }}: {{ item }}
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="modal fade" id="prizeModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <h1 class="modal-title fs-5" id="exampleModalLabel">{{ mobile }}</h1>
        </div>
        <div class="modal-body">
          <ul>
            <li v-for="item in prizes">
              {{ item.title }} - {{ convertToJalali(item.created_at, false) }}
            </li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="userModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <h1 class="modal-title fs-5" id="exampleModalLabel">{{ mobile }}</h1>
        </div>
        <div class="modal-body">
          <ul>
            <li v-for="item in userRequirementValues">
              {{ item.user_requirement.title }}: {{ item.value }}
            </li>
          </ul>
        </div>

      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ['wheel', 'users', 'sum_number_of_each_prize'],
  components: {

  },
  data: () => ({
    page: 1,
    per_page: null,

    prizes: [],
    mobile: '',

    userRequirementValues: [],

    search: {
      mobile: ''
    },

    _users: []
  }),
  watch: {
    'search.mobile': {
      handler(newValue, oldValue) {
        newValue === '' && this.submitSearch()
      }
    }
  },
  computed: {

  },
  methods: {
    submitSearch() {
      let _this = this
      axios.get(`${this.$root.apiUrl}/wheels/${this.wheel.slug}/search`, {
        params: this.search
      }).then(res => {

        _this._users = res.data.data.users

      })
    },
    setDataToPrizesModal(mobile, prizes) {

      this.mobile = mobile
      this.prizes = prizes

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

    setDataToUserModal(mobile, userRequirementValues) {

      this.mobile = mobile
      this.userRequirementValues = userRequirementValues

    }
  },
  created() {

  },
  mounted() {

    console.log(this.wheel);

    this._users = this.users



    // console.log(this.prizes_count);



  }
}
</script>



<style scoped></style>
