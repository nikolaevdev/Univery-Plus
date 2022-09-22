<template>
	<div class="admin-panel">
		<h5 class="mb-3 text-dark">{{$t('admin')}}</h5>
		<div class="row">
		    <div class="col-md-3">
		        <div class="menu-list menu-list-nav mb-3" id="menu-list-nav">
                    <div class="menu-list-module" v-for="tab in tabs" :key="tab.name" v-if="$can(tab.can)">
                    	<template v-if="tab.sections.length > 1">
				            <a class="menu-list-group-item list-group-item-action" data-toggle="collapse" :data-target="'#section-' + tab.name" aria-expanded="true" :aria-controls="'#section-' + tab.name">
	                            <fa :icon="tab.icon" fixed-width class="mr-2"/> {{ tab.text }} <span v-if="menu_badge[tab.name]" class="badge badge-primary align-middle float-right rounded-circle">{{ menu_badge[tab.name] }}</span>
		                    </a>

						    <div :id="'section-' + tab.name" class="collapse" :aria-labelledby="'#section-' + tab.name" data-parent="#menu-list-nav">
						      <router-link v-for="section in tab.sections" :key="section.route" class="menu-list-group-item list-group-item-action menu-list-group-item-collapse" :to="{ name: section.route }">
				                {{ section.name }}
				              </router-link>
						    </div>
					    </template>
					    <template v-else>
                          <router-link class="menu-list-group-item list-group-item-action" :to="{ name: tab.sections[0].route }">
			                <fa :icon="tab.icon" fixed-width class="mr-2"/> {{ tab.text }} <span v-if="menu_badge[tab.name]" class="badge badge-primary align-middle float-right rounded-circle">{{ menu_badge[tab.name] }}</span>
			              </router-link>
					    </template>
					</div>
				</div>
		    </div>

		    <div class="col-md-9">
		      <transition name="fade" mode="out-in">
		        <router-view />
		      </transition>
		    </div>
		</div>
	</div>
</template>

<script>
export default {
  middleware: 'auth',

  data: () => ({
    menu_badge: {
    	'user': 0,
    	'enroll': 0,
    	'vk_bot': 0,
    }
  }),

  computed: {
    tabs () {
      return [
        {
          icon: 'home',
          name: 'home',
          text: this.$t('admin_home'),
          sections: [
            {
                route: 'admin.home',
                name: this.$t('admin_home'),
            }
           ]
        },
        {
          icon: 'user',
          name: 'user',
          text: this.$t('admin_user'),
          can: 'управление пользователями',
          sections: [
            {
                route: 'admin.users',
                name: this.$t('admin_user'),
            }
           ]
        },
        {
          icon: 'lock',
          name: 'access_right',
          text: this.$t('admin_access_right'),
          can: 'управление пользователями',
          sections: [
            {
                route: 'admin.access_right',
                name: this.$t('admin_access_right'),
            }
           ]
        },
        {
          icon: 'newspaper',
          name: 'news',
          text: this.$t('admin_news'),
          can: 'новости',
          sections: [
            {
                route: 'admin.news.home',
                name: this.$t('admin_news_all'),
            },
            {
                route: 'admin.news.add',
                name: this.$t('admin_news_add'),
            }
           ]
        },
        {
          icon: 'file-alt',
          name: 'university_information',
          text: this.$t('admin_organization_information'),
          can: 'система',
          sections: [
            {
              route: 'admin.university_information.faculties',
              name: this.$t('admin_university_information_faculties'),
            },
            {
              route: 'admin.university_information.specialties',
              name: this.$t('admin_university_information_specialties'),
            },
            {
              route: 'admin.university_information.education_levels',
              name: this.$t('admin_university_information_education_levels'),
            },
            {
              route: 'admin.university_information.use',
              name: this.$t('admin_university_information_use'),
            },
            {
              route: 'admin.university_information.identification_documents_name',
              name: this.$t('admin_university_information_identification_documents_name'),
            },
            {
              route: 'admin.university_information.document_on_education_name',
              name: this.$t('admin_university_information_document_on_education_name'),
            },
            {
              route: 'admin.university_information.stage_of_education',
              name: this.$t('admin_university_stage_of_education'),
            }
           ]
        },
        {
          icon: 'th-list',
          name: 'applications',
          text: this.$t('admin_enroll'),
          can: 'управление пользователями',
          sections: [
            {
                route: 'admin.applications.list',
                name: this.$t('admin_applications_all'),
            }
           ]
        },
        {
          icon: 'file',
          name: 'documents',
          text: this.$t('admin_docs'),
          can: 'управление документами',
          sections: [
            {
                route: 'admin.documents.home',
                name: this.$t('admin_docs'),
            },
            {
                route: 'admin.documents.add',
                name: this.$t('admin_docs_add'),
            }
           ]
        },
        {
          icon: 'info',
          name: 'articles',
          text: this.$t('admin_info'),
          can: 'новости',
          sections: [
            {
                route: 'admin.articles.home',
                name: this.$t('admin_articles'),
            },
            {
                route: 'admin.articles.add',
                name: this.$t('admin_articles_add'),
            },
            {
                route: 'admin.articles.categories',
                name: this.$t('admin_articles_categories'),
            }
           ]
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
