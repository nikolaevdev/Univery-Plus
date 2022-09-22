import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import Permissions from './mixins/Permissions';

var eventHub = new Vue();

import '~/plugins'
import '~/components'

Vue.config.productionTip = false

Vue.mixin(Permissions);

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
})
