<template>
  <div class="module help">

    <div class="mb-3 d-flex justify-content-between">
      <h2 class="text-dark align-middle mb-0">{{$t('help')}}</h2>

      <router-link class="" :to="{ name: 'help.admin' }" v-if="$can('система')">
        {{$t('help_admin')}}
      </router-link>
    </div>

    <div class="bg-white rounded mb-2 p-3">
      <section class="help-module mb-3 p-3 border shadow-sm" v-for="(module, index) in helplist" :key="module.module">
        <h4 class="mb-3">{{module.name}}</h4>
        <div class="help-module-steps">
          <div class="help-module-step  mb-2 p-2" v-for="(step, index) in module.steps" :key="step.name">
            <p v-html="step.text" class="mb-0"></p>
            <div v-for="(src, index) in step.images" :key="index" class="d-flex flex-column p-2 ">
              <img :src="src.src" class="help-img border">
              <em>{{src.title}}</em>
            </div>
          </div>
        </div>
      </section>
    </div>

  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import i18n from '~/plugins/i18n'

export default {

  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('help') }
  },

  data() {
	  return {
      helplist: [
        { 
          module: 'main', 
          name: this.$t('help__module__main'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help__module__main_step_1'), 
              images: [
                {
                  src: "/img/help/0.jpg",
                  title: this.$t('help__module__main_step_1_img'),
                },
              ] ,
            }
          ],
        },
        { 
          module: 'authorization', 
          name: this.$t('help__module__authorization'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help__module__authorization_step_1'), 
              images: [
              ] ,
            },
            { 
              name: '2',
              text: this.$t('help__module__authorization_step_2'), 
              images: [
                {
                  src: "/img/help/1.jpg",
                  title: this.$t('help__module__authorization_step_2_img'),
                },
              ] ,
            },
            { 
              name: '3',
              text: this.$t('help__module__authorization_step_3'), 
              images: [
              ],
            },
            { 
              name: '4',
              text: this.$t('help__module__authorization_restore_link'), 
              images: [
              ],
            },
            { 
              name: '5',
              text: this.$t('help__module__authorization_vk'), 
              images: [
                {
                  src: "/img/help/1.1.jpg",
                  title: this.$t('help__module__authorization_vk_img'),
                },
              ],
            },
          ],
        },
        { 
          module: 'registration', 
          name: this.$t('help__module__registration'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help__module__registration_step_1'), 
              images: [
              ] ,
            },
            { 
              name: '2',
              text: this.$t('help__module__registration_step_2'), 
              images: [
                {
                  src: "/img/help/2.jpg",
                  title: this.$t('help__module__registration_step_2_img'),
                },
              ] ,
            },
            { 
              name: '3',
              text: this.$t('help__module__registration_step_3'), 
              images: [
              ],
            },
            { 
              name: '5',
              text: this.$t('help__module__registration_vk'), 
              images: [
                {
                  src: "/img/help/2.1.jpg",
                  title: this.$t('help__module__registration_vk_img'),
                },
              ],
            },
          ],
        },
        { 
          module: 'passwordrecovery', 
          name: this.$t('help__module__passwordrecovery'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help__module__passwordrecovery_step_1'), 
              images: [
              ] ,
            },
            { 
              name: '2',
              text: this.$t('help__module__passwordrecovery_step_2'), 
              images: [
                {
                  src: "/img/help/3.jpg",
                  title: this.$t('help__module__passwordrecovery_step_2_img'),
                },
              ] ,
            },
            { 
              name: '3',
              text: this.$t('help__module__passwordrecovery_step_3'), 
              images: [
              ],
            },
            { 
              name: '4',
              text: this.$t('help__module__passwordrecovery_step_4'), 
              images: [
                {
                  src: "/img/help/3-2.jpg",
                  title: this.$t('help__module__passwordrecovery_step_4_img'),
                },
              ] ,
            },
            { 
              name: '5',
              text: this.$t('help__module__passwordrecovery_step_5'), 
              images: [
                {
                  src: "/img/help/3-2.2.jpg",
                  title: this.$t('help__module__passwordrecovery_step_5_img'),
                },
              ] ,
            },

          ],
        },
        { 
          module: 'info', 
          name: this.$t('help__module__info'), 
          steps: [
            { 
              name: '0',
              text: this.$t('help__module__info_step_0'), 
              images: [
              ] ,
            },
            { 
              name: '1',
              text: this.$t('help__module__info_step_1'), 
              images: [
                {
                  src: "/img/help/4.jpg",
                  title: this.$t('help__module__info_step_1_img'),
                },
                {
                  src: "/img/help/4.1.jpg",
                  title: this.$t('help__module__info_step_2_img'),
                },
                {
                  src: "/img/help/4.2.jpg",
                  title: this.$t('help__module__info_step_3_img'),
                },
                {
                  src: "/img/help/4.3.jpg",
                  title: this.$t('help__module__info_step_4_img'),
                }
              ] ,
            }
          ],
        },
        { 
          module: 'settings', 
          name: this.$t('help__module__settings'), 
          steps: [
            { 
              name: '0',
              text: this.$t('help__module__settings_step_0'), 
              images: [
                {
                  src: "/img/help/5.jpg",
                  title: this.$t('help__module__settings_step_0_img'),
                },
              ] ,
            },
            { 
              name: '1',
              text: this.$t('help__module__settings_step_1'), 
              images: [
                {
                  src: "/img/help/5-2.jpg",
                  title: this.$t('help__module__settings_step_1_img'),
                },
              ] ,
            }
          ],
        },
        { 
          module: 'entrance', 
          name: this.$t('help__module__entrance'), 
          steps: [
            { 
              name: '0',
              text: this.$t('help__module__entrance_step_0'), 
              images: [
              ] ,
            },
            { 
              name: '1',
              text: this.$t('help__module__entrance_step_1'), 
              images: [
                {
                  src: "/img/help/6.jpg",
                  title: this.$t('help__module__entrance_step_1_img'),
                },
              ] ,
            },
            { 
              name: '2',
              text: this.$t('help__module__entrance_step_2'), 
              images: [
                {
                  src: "/img/help/6-2.jpg",
                  title: this.$t('help__module__entrance_step_2_img'),
                },
              ] ,
            },
            { 
              name: '3',
              text: this.$t('help__module__entrance_step_3'), 
              images: [
                {
                  src: "/img/help/6-3.jpg",
                  title: this.$t('help__module__entrance_step_3_img'),
                },
              ] ,
            },
            { 
              name: '4',
              text: this.$t('help__module__entrance_step_4'), 
              images: [
                {
                  src: "/img/help/6-4.jpg",
                  title: this.$t('help__module__entrance_step_4_img'),
                },
                {
                  src: "/img/help/6-4.1.jpg",
                  title: this.$t('help__module__entrance_step_4_2_img'),
                },
                {
                  src: "/img/help/6-4.2.jpg",
                  title: this.$t('help__module__entrance_step_4_3_img'),
                },
              ] ,
            },
            { 
              name: '5',
              text: this.$t('help__module__entrance_step_5'), 
              images: [
                {
                  src: "/img/help/6-5.jpg",
                  title: this.$t('help__module__entrance_step_5_img'),
                },
              ] ,
            },
            { 
              name: '6',
              text: this.$t('help__module__entrance_step_6'), 
              images: [
                {
                  src: "/img/help/6-6.jpg",
                  title: this.$t('help__module__entrance_step_6_img'),
                },
                {
                  src: "/img/help/6-6.2.jpg",
                  title: this.$t('help__module__entrance_step_6_2_img'),
                },
              ] ,
            },
            { 
              name: '7',
              text: this.$t('help__module__entrance_step_7'), 
              images: [
                {
                  src: "/img/help/6-7.jpg",
                  title: this.$t('help__module__entrance_step_7_img'),
                },
                {
                  src: "/img/help/6-7.2.jpg",
                  title: this.$t('help__module__entrance_step_7_2_img'),
                },
                {
                  src: "/img/help/6-7.3.jpg",
                  title: this.$t('help__module__entrance_step_7_3_img'),
                },
              ] ,
            },
            { 
              name: '8',
              text: this.$t('help__module__entrance_step_8'), 
              images: [
                {
                  src: "/img/help/6-8.jpg",
                  title: this.$t('help__module__entrance_step_8_img'),
                },
              ] ,
            },
            { 
              name: '9',
              text: this.$t('help__module__entrance_step_9'), 
              images: [
                {
                  src: "/img/help/6-9.jpg",
                  title: this.$t('help__module__entrance_step_9_img'),
                },
                {
                  src: "/img/help/6-9.2.jpg",
                  title: this.$t('help__module__entrance_step_9_2_img'),
                },
              ] ,
            }
          ],
        },
      ]
	  }
  },
   
}
</script>
