<template>
  <div class="min-vh-100 row bg-white radius-wrap">
    <div class="d-flex align-items-md-center col-md-8 col-lg-6 col-xl-5 top-left-border-radius">
      <div class="w-100 py-5 px-md-5 px-xl-6 position-relative">
        <div class="mb-4">
          <h2>{{ $t('reset_password') }}</h2>
        </div>
        <form class="form-validate" @submit.prevent="send" @keydown="form.onKeydown($event)">
          <alert-success :form="form" :message="status" />
          <div class="form-group">
            <label for="resetEmail" class="form-label">{{ $t('email') }}</label>
            <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email" id="resetEmail" required="">
            <has-error :form="form" field="email" />
          </div>
          <v-button :loading="form.busy" class="btn-primary btn-lg btn-block">
            {{ $t('send_password_reset_link') }}
          </v-button>
          </form> 
        </div> 
      </div> 
      <div class="d-none d-md-block col-md-4 col-lg-6 col-xl-7 top-right-border-radius" rounded-right> 
        <div class="bg-cover h-100 mr-n3 top-right-border-radius" style="background-image: url(&quot;/img/photo/student2.jpg&quot;);"></div> 
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
      email: ''
    })
  }),

  methods: {
    async send () {
      const { data } = await this.form.post('/api/password/email')

      this.status = data.status

      this.form.reset()
    }
  }
}
</script>
