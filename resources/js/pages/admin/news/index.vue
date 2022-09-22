<template>
  <div class="row">
    <div class="col-12">
      <h5 class="mb-2 text-dark">
        <router-link class="" :to="{ name: 'admin.users' }">
          {{$t('admin')}}
        </router-link>
      </h5>
      <h2 class="mb-3 text-dark">{{$t('admin_news')}}</h2>
      <div class="bg-light p-3">
	      <div class="d-flex flex-row">
          <router-link class="py-2 px-3 mr-2 border rounded-pill tab-link" :to="{ name: tab.route }"  active-class="bg-primary text-white"  v-for="tab in tabs" :key="tab.name">
              {{ tab.text }}
          </router-link>
        </div>
		  </div>
    </div>

    <div class="col-12">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  computed: {
    tabs () {
      return [
        {
          name: 'name',
          text: this.$t('admin_news'),
          route: 'admin.news.home'
        },
        {
          name: 'categories',
          text: this.$t('admin_news_categories'),
          route: 'admin.news.categories'
        },
        {
          name: 'add',
          text: this.$t('admin_news_add'),
          route: 'admin.news.add'
        }
      ]
    }
  }
}
</script>