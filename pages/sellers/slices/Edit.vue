<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-3">
        افزودن فایل اکسل کد های تخفیف
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
      <div class="col-6">
        <div class="alert alert-primary" role="alert">
          مجموع احتمالات دیگر اسلایس ها:
          {{ this.sum_probability - this.slice.probability }}
          درصد
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-6">
        <div class="mt-4 form-check">
          <input type="checkbox" class="form-check-input" id="status" @change="changeStatus" :checked="_slice.status">
          <label class="form-check-label" for="status">نمایش تعداد برنده های این اسلایس در صفحه گردونه</label>
        </div>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-4">
        عنوان
      </div>

      <div class="col-1">
        احتمال(درصد)
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

    <div class="row mt-3">
      <div class="col-4">
        <input type="text" class="form-control" v-model="_slice.title">
      </div>

      <div class="col-1">
        <input type="number" class="form-control" v-model="_slice.probability">
      </div>

      <div class="col-4">
        <textarea type="number" class="form-control" rows="1" v-model="_slice.description">
            </textarea>
      </div>

      <div class="col-2">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#discountCodeModal"
          v-if="_discount_codes_exists" @click="fetchDiscountCodes">
          کد های تخفیف
          ({{ _slice.discount_codes_not_used_count }})
        </button>
        <input v-else type="number" class="form-control" v-model="_slice.inventory">
      </div>

      <div class="col-2">

      </div>

    </div>

    <div class="row mt-5">
      <div class="col-6">
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

export default {
  props: ['slice', 'sum_probability', 'discount_codes_exists'],
  components: {

  },
  data: () => ({
    discountCodeFile: null,
    discountCodes: [],
    page: 1,
    per_page: null,
    _slice: {},
    status: null,
    _discount_codes_exists: 0
  }),
  computed: {

  },
  methods: {
    submit() {
      if ((this.sum_probability - this.slice.probability) + this._slice.probability > 100) {
        alert('مجموع احتمالات نباید بیشتر از 100 درصد باشد..')
        return
      }

      axios.put(`${this.$root.apiUrl}/sellers/slices/${this.slice.id}`, this._slice).then(res => {

      }).catch(e => {
        alert(e.response.data.message)
      })
    },
    discountCodeFileChanged(e) {
      const target = e.target
      this.discountCodeFile = (target && target.files) && target.files[0]
    },
    fetchDiscountCodes() {
      let _this = this

      axios.get(`${this.$root.apiUrl}/sellers/discount_codes/${this._slice.id}`
      ).then(res => {

        _this.discountCodes = res.data.data.discount_codes.data

      }).catch(e => {
        alert(e.response.data.message)
      })
    },
    deleteDiscountCode() {
      let _this = this
      axios.delete(`${this.$root.apiUrl}/sellers/discount_codes/${this._slice.id}`
      ).then(res => {

        _this.discountCodes = res.data.data.discount_codes.data
        _this._slice = res.data.data.slice
        _this._discount_codes_exists = res.data.data.discount_codes_exists

        if (!_this.discountCodes.length) {
          let modalEl = document.getElementById('discountCodeModal')
          let modal = bootstrap.Modal.getInstance(modalEl)
          modal.hide()
        }

      }).catch(e => {
        alert(e.response.data.message)
      })
    },
    submitDiscountCode() {
      const config = {
        headers: { 'content-type': 'multipart/form-data' },
      }

      let formData = new FormData

      formData.append('slice_id', this._slice.id)
      formData.append('file', this.discountCodeFile)

      let _this = this

      axios.post(`${this.$root.apiUrl}/sellers/discount_codes`, formData, config).then(res => {

        _this._slice = res.data.data.slice
        _this._discount_codes_exists = res.data.data.discount_codes_exists

      }).catch(e => {
        alert(e.response.data.message)
      })

    },
    changeStatus(e) {
      this._slice.status = e.target.checked ? 1 : null
    },
  },
  created() {

  },
  mounted() {
    this._slice = { ...this.slice }
    this._discount_codes_exists = this.discount_codes_exists
  }
}
</script>

<style scoped></style>
