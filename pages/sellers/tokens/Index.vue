<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-12">
        <div class="mt-4 border p-3 rounded">
          <div class="mb-3">
            <span class="ms-3">ایجاد توکن:</span>
            <input type="number" min="1" max="500" v-model="tokenNum">
            <button @click="submitCreate">ایجاد</button>
          </div>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="end" @change="changeCheckBoxEndAt" :checked="!flagEndAt">
            <label class="form-check-label" for="end">تاریخ انقضا نداشته باشد</label>
          </div>

          <Transition>
            <div class="mt-4 p-0" v-if="flagEndAt">
              <date-picker v-model="endAt" type="datetime" label="تاریخ انقضا"></date-picker>
            </div>
          </Transition>
        </div>
      </div>

      <div class="col-6 mt-5">
        <a :href="`${$root.apiUrl}/tokens/download-excel/${this.slug}`" target="_blank" class="btn btn-success btn-sm">
          اکسل توکن های استفاده نشده
        </a>
      </div>

      <div class="col-6 mt-5">
        <button class="btn btn-danger btn-sm" @click="deleteNotUsedTokens">حذف توکن های استفاده نشده</button>
      </div>

      <div class="col-12 mt-5">
        <table class="table">
          <thead>
            <tr>
              <th></th>
              <th>توکن</th>
              <th>موبایل</th>
              <th>انقضا</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td>
                <input v-model="search.token" @keyup.enter="submitSearch(1, 'token')">
              </td>
              <td>
                <input v-model="search.mobile" @keyup.enter="submitSearch(1, 'mobile')">
              </td>
              <td></td>
            </tr>
            <tr v-for="(item, index) in _tokens.data">
              <td> {{ ((page - 1) * perPage) + (index + 1) }}</td>
              <td>{{ item.value }}</td>
              <td>{{ item.user?.mobile }}</td>
              <td>{{ convertToJalali(item.end_at) }}</td>
            </tr>
          </tbody>
        </table>

        <div class="col-12">
          <div class="d-flex flex-row">
            <div class="ms-3">
              <button v-if="_tokens.prev_page_url" @click="submitSearch(_tokens.current_page - 1)">
                قبل
              </button>
            </div>
            <div class="ms-3">
              {{ _tokens.current_page }}
            </div>
            <div class="ms-3">
              <button v-if="_tokens.next_page_url" @click="submitSearch(_tokens.current_page + 1)">
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
import DatePicker from 'vue3-persian-datetime-picker'
import { Link } from '@inertiajs/vue3'

export default {
  props: ['slug', 'tokens'],
  components: {
    DatePicker,
    Link
  },
  data: () => ({
    tokenNum: 100,
    flagEndAt: true,
    endAt: null,
    holderEndAt: null,
    search: {
      token: null,
      mobile: null
    },
    _tokens: [],
    currentPage: 1,
    page: 1,
    perPage: null,
  }),
  computed: {
  },
  methods: {
    submitCreate() {
      let endAtDate = null
      let endAtHour = null
      let endJalali = null
      let _this = this

      if (this.endAt) {
        endAtDate = this.endAt.split(" ")[0].split('/')
        endAtHour = this.endAt.split(" ")[1]

        endJalali = this.jalaliToGregorian(
          parseInt(endAtDate[0]),
          parseInt(endAtDate[1]),
          parseInt(endAtDate[2])
        ).join('-')
      }

      axios.post(`${this.$root.apiUrl}/tokens/${this.slug}`, {
        token_num: this.tokenNum,
        end_at: this.endAt && `${endJalali} ${endAtHour}`,
      }).then(res => {
        console.log(res.data.data.tokens);
        _this._tokens = res.data.data.tokens
        _this.perPage = res.data.data.tokens.per_page
      })
    },
    changeCheckBoxEndAt(e) {
      this.flagEndAt = !e.target.checked
      this.endAt = e.target.checked ? null : this.holderEndAt
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
    submitSearch(page = 1, param = null) {
      if (param === 'token') this.search.mobile = null
      if (param === 'mobile') this.search.token = null

      let _this = this
      axios.get(`${this.$root.apiUrl}/tokens/search/${this.slug}?page=${page}`, {
        params: this.search
      }).then(res => {
        _this._tokens = res.data.data.tokens

        _this.perPage = res.data.data.tokens.per_page
        _this.page = page
      })
    },
    deleteNotUsedTokens() {
      let _this = this
      axios.delete(`${this.$root.apiUrl}/tokens/not-used/${this.slug}`
      ).then(res => {
        _this._tokens = res.data.data.tokens
      }).catch(e => {
        alert(e.response.data.message)
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
  },
  mounted() {
    this._tokens = { ...this.tokens }
    this.perPage = this._tokens.per_page
  }
}
</script>

<style scoped></style>


<!-- <div class="ms-3">
  <Link v-if="_tokens.prev_page_url" :href="_tokens.prev_page_url">قبل</Link>
</div>
<div class="ms-3">
  {{ _tokens.current_page }}
</div>
<div class="ms-3">
  <Link v-if="_tokens.next_page_url" :href="_tokens.next_page_url">بعدی</Link>
</div> -->
