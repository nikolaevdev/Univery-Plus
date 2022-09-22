<template>
  <div class="min-vh-100 row bg-white radius-wrap">
    <div class="d-flex align-items-md-center col-md-8 col-lg-6 col-xl-5 top-left-border-radius">
      <div class="w-100 py-5 px-md-5 px-xl-6 position-relative">
        <div class="mb-4">
          <h2>{{ $t('reset_password') }}</h2>
        </div>
        <form class="form-validate" @submit.prevent="reset" @keydown="form.onKeydown($event)">
          <alert-success :form="form" :message="status" />
          <div class="form-group">
            <label for="resetEmail" class="form-label">{{ $t('email') }}</label>
            <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email" id="resetEmail" readonly="">
            <has-error :form="form" field="email" />
          </div>
          <div class="form-group">
            <label for="resetPassword" class="form-label">{{ $t('password') }}</label>
            <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password" id="resetPassword" autofocus>
            <has-error :form="form" field="password" />
          </div>
          <div class="form-group">
            <label for="resetPasswordconfirm" class="form-label">{{ $t('confirm_password') }}</label>
            <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="confirm_password" id="resetPasswordconfirm" autofocus>
            <has-error :form="form" field="confirm_password" />
          </div>
          <v-button :loading="form.busy" class="btn-primary btn-lg btn-block">
            {{ $t('reset_password') }}
          </v-button>
          </form> 
        </div> 
      </div> 
      <div class="d-none d-md-block col-md-4 col-lg-6 col-xl-7 top-right-border-radius" rounded-right> 
        <div class="bg-cover h-100 mr-n3 top-right-border-radius photo-student2"></div> 
      </div> 
    </div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    status: '',
    form: new Form({
      token: '',
      email: '',
      password: '',
      password_confirmation: ''
    })
  }),

  created () {
    this.form.email = this.$route.query.email
    this.form.token = this.$route.params.token
  },

  methods: {
    async reset () {
      const { data } = await this.form.post('/api/password/reset')

      this.status = data.status

      this.form.reset()
    }
  }
}
</script>
