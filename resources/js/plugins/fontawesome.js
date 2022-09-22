import Vue from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// import { } from '@fortawesome/free-regular-svg-icons'

import {
  faUser, faLock, faSignOutAlt, faCog, faAngleRight, faFile,faChevronCircleDown, faHome, faChartPie, faNewspaper, faThList, faInfo, faFileAlt
} from '@fortawesome/free-solid-svg-icons'

import {
  faVk
} from '@fortawesome/free-brands-svg-icons'

library.add(
  faUser, faLock, faSignOutAlt, faCog, faVk, faAngleRight, faFile, faChevronCircleDown, faHome, faChartPie, faNewspaper, faThList, faInfo, faFileAlt
)

Vue.component('fa', FontAwesomeIcon)
