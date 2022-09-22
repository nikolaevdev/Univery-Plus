<template>
<div class="min-vh-100 row bg-white radius-wrap">
	<div class="d-flex align-items-md-center col-md-8 col-lg-6 col-xl-5 top-left-border-radius">
		<div class="w-100 py-5 px-md-5 px-xl-6 position-relative">
			<div class="mb-5">
				<h2>{{ $t('login_header') }}</h2>
			</div>
			<form class="form-validate" @submit.prevent="login" @keydown="form.onKeydown($event)">
				<div class="form-group">
					<label for="loginEmail" class="form-label">{{ $t('email') }}</label>
					<input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email" id="loginEmail" required="">
					<has-error :form="form" field="email" />
				</div>
				<div class="mb-4 form-group">
					<div class="row">
						<div class="col">
							<label for="loginPassword" class="form-label">{{ $t('password') }}</label>
						</div>
						<div class="col-auto">
							<router-link :to="{ name: 'password.request' }" class="form-text small">
				               {{ $t('forgot_password') }}
				            </router-link>
						</div>
					</div>
					<input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password" id="loginPassword" required=""></div>
					<has-error :form="form" field="password" />
					<div class="mb-4 form-group">
						<div class="text-muted custom-checkbox custom-control">
							<input id="loginRemember" type="checkbox" class="custom-control-input" v-model="remember" name="remember">
							<label class="custom-control-label" for="loginRemember">
								<span class="text-sm">{{ $t('remember_me') }}</span> 
							</label> 
						</div> 
					</div>
					<v-button :loading="form.busy" class="btn-primary btn-lg btn-block">
	                {{ $t('login') }}
	                </v-button>
					<hr data-content="OR" class="my-3 hr-text letter-spacing-2">
					<login-with-vk class="btn-social mb-3 btn btn-outline-primary btn-block"/>
					<hr class="my-4">
					<p class="text-center">
						<small class="text-muted text-center">{{ $t('login_sign_text') }}&nbsp;
							<router-link :to="{ name: 'register' }">
				                {{ $t('register') }}
				            </router-link>
						</small> 
					</p> 
				</form> 
			</div> 
		</div> 
		<div class="d-none d-md-block col-md-4 col-lg-6 col-xl-7 top-right-border-radius" rounded-right> 
			<div class="bg-cover h-100 mr-n3 top-right-border-radius photo-student1"></div> 
		</div> 
	</div>
</template>

<script>
import Form from 'vform'
import LoginWithVk from '~/components/LoginWithVk'

export default {
  middleware: 'guest',

  components: {
    'login-with-vk': LoginWithVk
  },

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
  	 appName: window.config.appName,
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'home' })
    }
  }
}
</script>
