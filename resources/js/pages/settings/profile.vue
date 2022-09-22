<template>
   <div class="module">
   	    <div class="module-wrap p-4  bg-white rounded">
   	    	<h5 class="text-dark mb-4 font-weight-light">{{$t('your_info')}}</h5>
   	    	<form @submit.prevent="update" @keydown="form.onKeydown($event)">
		      <alert-success :form="form" :message="$t('info_updated')" />

		      <!-- Name -->
		      <div class="form-group row">
		        <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
		        <div class="col-md-7">
		          <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" type="text" name="name">
		          <has-error :form="form" field="name" />
		        </div>
		      </div>

		      <!-- Email -->
		      <div class="form-group row">
		        <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
		        <div class="col-md-7">
		          <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
		          <has-error :form="form" field="email" />
		        </div>
		      </div>

		      <!-- Submit Button -->
		      <div class="form-group row">
		        <div class="col-md-9 ml-md-auto">
		          <v-button :loading="form.busy" type="success">
		            {{ $t('update') }}
		          </v-button>
		        </div>
		      </div>
		    </form>
   	    </div>
   </div>

</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      name: '',
      email: ''
    })
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/api/settings/profile')

      this.$store.dispatch('auth/updateUser', { user: data })
    }
  }
}
</script>
