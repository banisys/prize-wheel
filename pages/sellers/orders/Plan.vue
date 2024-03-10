<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-12">
        <div class="p-3 border mt-4 rounded">
          <p class="fw-bold">پلن ها</p>

          <div class="form-check" v-for="(item, index) in plans">
            <input class="form-check-input" type="radio" name="plan" :id="`plan-${index}`" :value="item.id"
              v-model="planId">
            <label class="form-check-label" :for="`plan-${index}`">
              {{ item.title }}
            </label>
          </div>

        </div>
      </div>
      <div class="col-12">
        <button type="button" class="btn btn-success w-100 btn-sm mt-3" @click="submit">
          پرداخت
        </button>
      </div>

      <div class="col-12 mt-5">
        <table class="table">
          <thead>
            <tr>
              <th></th>
              <th>پلن</th>
              <th>مبلغ پرداختی</th>
              <th>انقضا</th>
              <th>تاریخ پرداخت</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in orders.data">
              <td> {{ ((page - 1) * perPage) + (index + 1) }}</td>
              <td>{{ item.title }}</td>
              <td>{{ numberFormat(item.payment) }}</td>
              <td>{{ convertToJalali(item.end_at) }}</td>
              <td>{{ convertToJalali(item.created_at, false) }}</td>
            </tr>
          </tbody>
        </table>

        <div class="col-12">
          <div class="d-flex flex-row">
            <div class="ms-3">
              <button v-if="orders.prev_page_url" @click="submitSearch(orders.current_page - 1)">
                قبل
              </button>
            </div>
            <div class="ms-3">
              {{ orders.current_page }}
            </div>
            <div class="ms-3">
              <button v-if="orders.next_page_url" @click="submitSearch(orders.current_page + 1)">
                بعدی
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  components: {

  },
  props: ['plans', 'orders'],
  data: () => ({
    planId: null,
    currentPage: 1,
    page: 1,
    perPage: null,
  }),
  computed: {

  },
  methods: {
    submit() {
      axios.post(`${this.$root.apiUrl}/sellers/orders/plan`, {
        plan_id: this.planId
      }).then(res => {

      })
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
    submitSearch(page = 1, param = null) {
      if (param === 'token') this.search.mobile = null
      if (param === 'mobile') this.search.token = null

      let _this = this
      axios.get(`${this.$root.apiUrl}/sellers/tokens/search/${this.slug}?page=${page}`, {
        params: this.search
      }).then(res => {
        _this._tokens = res.data.data.tokens

        _this.perPage = res.data.data.tokens.per_page
        _this.page = page
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
    numberFormat(price) {
      return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
  },
  created() {

  },
  mounted() {
    this.perPage = this.orders.per_page
  }
}
</script>

<style scoped></style>
