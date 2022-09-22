<template>
  <div class="module help">

    <div class="mb-3 d-flex justify-content-between">
      <h2 class="text-dark align-middle mb-0">{{$t('help_admin')}}</h2>
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
    return { title: this.$t('help_admin') }
  },

  data() {
	  return {
      helplist: [
         { 
          module: 'main', 
          name: this.$t('help_admin__module__main'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help_admin__module__main_step_1'), 
              images: [
                {
                  src: "/img/help/admin/0.jpg",
                  title: this.$t('help_admin__module__main_step_1_img'),
                }
              ] ,
            }
          ],
        },
        { 
          module: 'applications', 
          name: this.$t('help_admin__module__applications'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help_admin__module__applications_step_1'), 
              images: [
                {
                  src: "/img/help/admin/1.jpg",
                  title: this.$t('help_admin__module__applications_step_1_img'),
                },
                {
                  src: "/img/help/admin/1.0.jpg",
                  title: this.$t('help_admin__module__applications_step_1_0_img'),
                }
              ] ,
            },
            { 
              name: '2',
              text: this.$t('help_admin__module__applications_step_2'), 
              images: [
                {
                  src: "/img/help/admin/1.2.jpg",
                  title: this.$t('help_admin__module__applications_step_2_img'),
                }
              ] ,
            },
            { 
              name: '3',
              text: this.$t('help_admin__module__applications_step_3'), 
              images: [
                {
                  src: "/img/help/admin/1.3.jpg",
                  title: this.$t('help_admin__module__applications_step_3_img'),
                },
                {
                  src: "/img/help/admin/1.4.jpg",
                  title: this.$t('help_admin__module__applications_step_3_2_img'),
                },
              ] ,
            }
          ],
        },
        { 
          module: 'users', 
          name: this.$t('help_admin__module__users'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help_admin__module__users_step_1'), 
              images: [
                {
                  src: "/img/help/admin/2.jpg",
                  title: this.$t('help_admin__module__users_step_1_img'),
                },
                {
                  src: "/img/help/admin/2_1.jpg",
                  title: this.$t('help_admin__module__users_step_1_2_img'),
                },
                {
                  src: "/img/help/admin/2_2.jpg",
                  title: this.$t('help_admin__module__users_step_1_3_img'),
                },
                {
                  src: "/img/help/admin/2_3.jpg",
                  title: this.$t('help_admin__module__users_step_1_4_img'),
                },
                {
                  src: "/img/help/admin/2_4.jpg",
                  title: this.$t('help_admin__module__users_step_1_5_img'),
                },
                {
                  src: "/img/help/admin/2_5.jpg",
                  title: this.$t('help_admin__module__users_step_1_6_img'),
                },
                {
                  src: "/img/help/admin/2_6.jpg",
                  title: this.$t('help_admin__module__users_step_1_7_img'),
                }
              ] ,
            }
          ],
        },
        { 
          module: 'access_right', 
          name: this.$t('help_admin__module__access_right'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help_admin__module__access_right_step_1'), 
              images: [
                {
                  src: "/img/help/admin/3.jpg",
                  title: this.$t('help_admin__module__access_right_step_1_img'),
                },
                {
                  src: "/img/help/admin/3_1.jpg",
                  title: this.$t('help_admin__module__access_right_step_1_2_img'),
                },
                {
                  src: "/img/help/admin/3_2.jpg",
                  title: this.$t('help_admin__module__access_right_step_1_3_img'),
                },
                {
                  src: "/img/help/admin/3_2_2.jpg",
                  title: this.$t('help_admin__module__access_right_step_1_3_2_img'),
                },
                {
                  src: "/img/help/admin/3_3.jpg",
                  title: this.$t('help_admin__module__access_right_step_1_4_img'),
                },
                {
                  src: "/img/help/admin/3_4.jpg",
                  title: this.$t('help_admin__module__access_right_step_1_5_img'),
                },
                {
                  src: "/img/help/admin/3_4_2.jpg",
                  title: this.$t('help_admin__module__access_right_step_1_6_img'),
                },
                {
                  src: "/img/help/admin/3_4_3.jpg",
                  title: this.$t('help_admin__module__access_right_step_1_7_img'),
                }
              ] ,
            }
          ],
        },
        { 
          module: 'news', 
          name: this.$t('help_admin__module__news'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help_admin__module__news_step_1'), 
              images: [
                {
                  src: "/img/help/admin/4.jpg",
                  title: this.$t('help_admin__module__news_step_1_img'),
                },
                {
                  src: "/img/help/admin/4_1.jpg",
                  title: this.$t('help_admin__module__news_step_1_2_img'),
                },
                {
                  src: "/img/help/admin/4_2.jpg",
                  title: this.$t('help_admin__module__news_step_1_3_img'),
                },
                {
                  src: "/img/help/admin/4_3.jpg",
                  title: this.$t('help_admin__module__news_step_1_3_2_img'),
                },
                {
                  src: "/img/help/admin/4_4.jpg",
                  title: this.$t('help_admin__module__news_step_1_4_img'),
                },
                {
                  src: "/img/help/admin/4_5.jpg",
                  title: this.$t('help_admin__module__news_step_1_5_img'),
                },
                {
                  src: "/img/help/admin/4_5_2.jpg",
                  title: this.$t('help_admin__module__news_step_1_5_2_img'),
                },
                {
                  src: "/img/help/admin/4_5_3.jpg",
                  title: this.$t('help_admin__module__news_step_1_5_3_img'),
                },
                {
                  src: "/img/help/admin/4_5_4.jpg",
                  title: this.$t('help_admin__module__news_step_1_5_4_img'),
                },
                {
                  src: "/img/help/admin/4_5_5.jpg",
                  title: this.$t('help_admin__module__news_step_1_5_5_img'),
                },
                {
                  src: "/img/help/admin/4_6.jpg",
                  title: this.$t('help_admin__module__news_step_1_6_img'),
                },
                {
                  src: "/img/help/admin/4_7.jpg",
                  title: this.$t('help_admin__module__news_step_1_7_img'),
                }
              ] ,
            }
          ],
        },
        { 
          module: 'info', 
          name: this.$t('help_admin__module__info'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help_admin__module__info_step_1'), 
              images: [
                {
                  src: "/img/help/admin/5.jpg",
                  title: this.$t('help_admin__module__info_step_1_img'),
                }
              ] ,
            },
            { 
              name: '2',
              text: this.$t('help_admin__module__info_step_2'), 
              images: [
                {
                  src: "/img/help/admin/5_2.jpg",
                  title: this.$t('help_admin__module__info_step_2'),
                },
                {
                  src: "/img/help/admin/5_2_2.jpg",
                  title: this.$t('help_admin__module__info_step_2_2_img'),
                }
              ],
            },
            { 
              name: '3',
              text: this.$t('help_admin__module__info_step_3'), 
              images: [
                {
                  src: "/img/help/admin/5_3.jpg",
                  title: this.$t('help_admin__module__info_step_3_img'),
                },
                {
                  src: "/img/help/admin/5_3_2.jpg",
                  title: this.$t('help_admin__module__info_step_3_2_img'),
                },
                {
                  src: "/img/help/admin/5_3_2_2.jpg",
                  title: this.$t('help_admin__module__info_step_3_2_img'),
                },
                {
                  src: "/img/help/admin/5_4.jpg",
                  title: this.$t('help_admin__module__info_step_3_3_img'),
                }
              ],
            },
            { 
              name: '4',
              text: this.$t('help_admin__module__info_step_4'), 
              images: [
                {
                  src: "/img/help/admin/5_5.jpg",
                  title: this.$t('help_admin__module__info_step_4_img'),
                },
                {
                  src: "/img/help/admin/5_5_2.jpg",
                  title: this.$t('help_admin__module__info_step_4_2_img'),
                },
                {
                  src: "/img/help/admin/5_5_3.jpg",
                  title: this.$t('help_admin__module__info_step_4_3_img'),
                },
              ],
            },
            { 
              name: '5',
              text: this.$t('help_admin__module__info_step_5'), 
              images: [
                {
                  src: "/img/help/admin/5_6.jpg",
                  title: this.$t('help_admin__module__info_step_5_1_img'),
                },
                {
                  src: "/img/help/admin/5_6_2.jpg",
                  title: this.$t('help_admin__module__info_step_5_2_img'),
                }
              ],
            },
            { 
              name: '6',
              text: this.$t('help_admin__module__info_step_6'), 
              images: [
                {
                  src: "/img/help/admin/5_7.jpg",
                  title: this.$t('help_admin__module__info_step_6_1_img'),
                },
                {
                  src: "/img/help/admin/5_7_2.jpg",
                  title: this.$t('help_admin__module__info_step_6_2_img'),
                }
              ],
            },
            { 
              name: '7',
              text: this.$t('help_admin__module__info_step_7'), 
              images: [
                {
                  src: "/img/help/admin/5_8.jpg",
                  title: this.$t('help_admin__module__info_step_7_1_img'),
                },
                {
                  src: "/img/help/admin/5_8_2.jpg",
                  title: this.$t('help_admin__module__info_step_7_2_img'),
                }
              ],
            },
            { 
              name: '8',
              text: this.$t('help_admin__module__info_step_8'), 
              images: [
                {
                  src: "/img/help/admin/5_9.jpg",
                  title: this.$t('help_admin__module__info_step_8_1_img'),
                },
                {
                  src: "/img/help/admin/5_9_2.jpg",
                  title: this.$t('help_admin__module__info_step_8_2_img'),
                }
              ],
            },
          ],
        },
        { 
          module: 'documents', 
          name: this.$t('help_admin__module__documents'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help_admin__module__documents_1_img'), 
              images: [
                {
                  src: "/img/help/admin/6.jpg",
                  title: this.$t('help_admin__module__documents_1_img'),
                },
                {
                  src: "/img/help/admin/6_2.jpg",
                  title: this.$t('help_admin__module__documents_step_1_2_img'),
                },
                {
                  src: "/img/help/admin/6_3.jpg",
                  title: this.$t('help_admin__module__documents_step_1_3_img'),
                },
                {
                  src: "/img/help/admin/6_4.jpg",
                  title: this.$t('help_admin__module__documents_step_1_4_img'),
                },
                {
                  src: "/img/help/admin/6_5.jpg",
                  title: this.$t('help_admin__module__documents_step_1_5_img'),
                }
              ] ,
            }
          ],
        },
        { 
          module: 'articles', 
          name: this.$t('help_admin__module__articles'), 
          steps: [
            { 
              name: '1',
              text: this.$t('help_admin__module__articles_1'), 
              images: [
                {
                  src: "/img/help/admin/7.jpg",
                  title: this.$t('help_admin__module__articles_1'),
                },
                {
                  src: "/img/help/admin/7_2.jpg",
                  title: this.$t('help_admin__module__articles_2'),
                },
                {
                  src: "/img/help/admin/7_2_2.jpg",
                  title: this.$t('help_admin__module__articles_3'),
                },
                {
                  src: "/img/help/admin/7_2_3.jpg",
                  title: this.$t('help_admin__module__articles_4'),
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
