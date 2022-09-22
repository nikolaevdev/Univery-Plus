<template>
  <div class="info-wraper">
    <header class="info-header p-3 p-lg-4 border-bottom bg-light">
      <div class="container-fluid">
        <h1 class="mb-3 text-dark text-center text-lg-left">{{$t('info')}}</h1>
        <div class="d-flex flex-row">
          <router-link class="py-2 px-3 mr-2 border rounded-pill bg-white info-tab-link" :to="{ name: tab.route }"  v-for="tab in tabs" :key="tab.name">
            {{ tab.text }}
          </router-link>
        </div>
      </div>
    </header>
    <main>
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </main>
  </div>
</template>

<script>
export default {
   layout: 'fluid',

   computed: {
    tabs () {
      return [
        {
          name: 'news',
          text: this.$t('info_news'),
          route: 'info.news',
        },
        {
          name: 'articles',
          text: this.$t('info_articles'),
          route: 'info.articles',
        },
        {
          name: 'documents',
          text: this.$t('info_documents'),
          route: 'info.documents',
        },
      ]
    }
  }
}
</script>

