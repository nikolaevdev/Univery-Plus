<template>
  <div class="module">
    <div class="row">
      <div class="col-sm-6 col-12">
        <div class="module-block p-4 bg-white rounded">
          <h6 class="text-dark mb-4 font-weight-bold">{{$t('admin_enroll_new')}}</h6>
          <span class="module-block-num font-weight-normal text-muted">+1</span>
        </div>
      </div>
      <div class="col-sm-6 col-12">
        <div class="module-block p-4 bg-white rounded">
          <h6 class="text-dark mb-4 font-weight-bold">{{$t('admin_users_new')}}</h6>
          <span class="module-block-num font-weight-normal text-muted">+1</span>
        </div>
      </div>
    </div>
   </div>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('admin') }
  },

  data: () => ({
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
   
  }
}
</script>
