<template>
  <div class="min-vh-100 row bg-white radius-wrap">
    <div class="d-flex align-items-md-center col-md-8 col-lg-6 col-xl-5 top-left-border-radius">
      <div class="w-100 py-5 px-md-5 px-xl-6 position-relative">
        <div class="mb-5">
          <h2>{{ $t('register_header') }}</h2>
        </div>
        <div class="mb-4">
          <label class="form-label">{{ $t('documents_to_read') }}</label>

          <div class="list-group" v-if="!isNull(articles) && islength(articles) && !error && !loading">

            <router-link v-for="(item, index) in articles" :key="item.id" :to="{ name: 'info.articles.get', params: { id: item.id } }" class="list-group-item list-group-item-action"><fa :icon="['fas', 'file']" class="mr-3"/>{{item.name}}</router-link>
            <router-link :to="{ name: 'info.articles.get' }"  class="list-group-item list-group-item-action text-center">
              <fa :icon="['fas', 'chevron-circle-down']" class="mr-3"/> {{ $t('view_all') }}
            </router-link>
          </div>
            
        </div>
        <form class="form-validate" @submit.prevent="register" @keydown="form.onKeydown($event)">
          <div class="alert alert-success" role="alert" v-if="mustVerifyEmail">
            {{ $t('verify_email_address') }}
          </div>
          <div class="form-group">
            <label for="registerName" class="form-label">{{ $t('name') }}</label>
            <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" type="text" name="name" id="registerName" required="">
            <has-error :form="form" field="name" />
          </div>
          <div class="form-group">
            <label for="registerEmail" class="form-label">{{ $t('email') }}</label>
            <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email" id="registerEmail" required="">
            <has-error :form="form" field="email" />
          </div>
          <div class="form-group">
            <label for="registerPassword" class="form-label">{{ $t('password') }}</label>
            <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password" id="registerPassword" required="">
            <has-error :form="form" field="password" />
          </div>
          <div class="form-group">
            <label for="registerPassword_confirmation" class="form-label">{{ $t('confirm_password') }}</label>
            <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="password_confirmation" id="registerPassword_confirmation" required="">
            <has-error :form="form" field="password_confirmation" />
          </div>
          <v-button :loading="form.busy" class="btn-primary btn-lg btn-block">
            {{ $t('register') }}
          </v-button>
          <hr data-content="OR" class="my-3 hr-text letter-spacing-2">
          <register-with-vk class="btn-social mb-3 btn btn-outline-primary btn-block"/>
          <hr class="my-4">
          <p class="text-center">
              <small class="text-muted text-center">{{ $t('register_sign_text') }}&nbsp;
                <router-link :to="{ name: 'login' }">
                          {{ $t('login') }}
                      </router-link>
              </small> 
          </p> 
        </form> 
      </div> 
    </div> 
    <div class="d-none d-md-block col-md-4 col-lg-6 col-xl-7 top-right-border-radius" rounded-right> 
      <div class="bg-cover h-100 mr-n3 top-right-border-radius photo-student3"></div> 
    </div> 
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import RegisterWithVk from '~/components/RegisterWithVk'
import i18n from '~/plugins/i18n'
import Swal from 'sweetalert2'
import { variables } from '~/mixins/variables'

export default {

  mixins: [variables],
  
  middleware: 'guest',

  components: {
    'register-with-vk': RegisterWithVk
  },

  metaInfo () {
    return { title: this.$t('register') }
  },

  data: () => ({
    form: new Form({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }),
    mustVerifyEmail: false,
    loading: false,
    error: null,
    articles: null,
  }),

  created() {
    this.fetchData()
  },

  watch: {
    $route: 'fetchData',
  },

  methods: {
    fetchData() {
      this.error = this.articles = null;
      this.articles_count = 0;
      this.loading = true;

      axios.get('/api/articles.regpage.get')
      .then(response => {
        this.articles = response.data;
      }) 
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    async register () {
      // Register the user.
      const { data } = await this.form.post('/api/register')

      // Must verify email fist.
      if (data.status) {
        this.mustVerifyEmail = true
      } else {
        // Log in the user.
        const { data: { token } } = await this.form.post('/api/login')

        // Save the token.
        this.$store.dispatch('auth/saveToken', { token })

        // Update the user.
        await this.$store.dispatch('auth/updateUser', { user: data })

        // Redirect home.
        this.$router.push({ name: 'home' })
      }
    },
    isNull(data){
      return this.empty(data);
    },
    islength(data){
      return this.isLength(data);
    }
  }
}
</script>
