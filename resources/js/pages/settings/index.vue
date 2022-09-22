<template>
  <div class="row">
    <div class="col-md-3">
        <h4 class="mb-3 text-dark">{{$t('settings')}}</h4>

        <div class="menu-list mb-3">
        	<router-link v-for="tab in tabs" :key="tab.route" class="menu-list-group-item list-group-item-action" :to="{ name: tab.route }" active-class="active">
              <fa :icon="tab.icon" fixed-width class="mr-2"/> {{ tab.name }}
            </router-link>
		</div>
    </div>

    <div class="col-md-9">
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
          icon: 'user',
          name: this.$t('profile'),
          route: 'settings.profile'
        },
        {
          icon: 'lock',
          name: this.$t('password'),
          route: 'settings.password'
        }
      ]
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>
