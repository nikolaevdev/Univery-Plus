function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },
  { path: '/help', name: 'help', component: page('help.vue') },
  { path: '/help/admin', name: 'help.admin', component: page('help_admin.vue') },
  { path: '/info',
    component: page('info/index.vue'),
    children: [
      { path: '', redirect: { name: 'info.news' } },
      { path: 'news', name: 'info.news', component: page('info/news.vue') },
      { path: 'news/:id', name: 'info.news.get', component: page('info/get_news.vue') },
      { path: 'articles', name: 'info.articles', component: page('info/articles.vue') },
      { path: 'articles/:id', name: 'info.articles.get', component: page('info/get_articles.vue') },
      { path: 'documents', name: 'info.documents', component: page('info/documents.vue') },
    ]
  },    
  { path: '/home', name: 'home',  redirect: { name: 'statement.my' } },
  { path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ] },
    { path: '/statement',
    component: page('statement/index.vue'),
    children: [
      { path: '', redirect: { name: 'statement.step1' } },
      { path: 'my', name: 'statement.my', component: page('statement/my.vue') },
      { path: 'my_form', name: 'statement.my_form', component: page('statement/my_form.vue') },
      { path: 'my_form_complete', name: 'statement.my_form.complete', component: page('statement/my_form_complete.vue') },
      { path: 'my_form_files', name: 'statement.my_form.files', component: page('statement/my_form_files.vue') },
      { path: 'my_form_edit', name: 'statement.my_form.edit', component: page('statement/my_form_edit.vue') },
      { path: 'application/creat', name: 'statement.application.creat', component: page('statement/application_creat.vue') },
      { path: 'application/step2', name: 'statement.application.step2', component: page('statement/application_creat_step2.vue') },
      { path: 'application/:id', name: 'statement.application.my', component: page('statement/application_my.vue') },
      { path: 'application/:id/edit/', name: 'statement.application.my_edit', component: page('statement/application_my_edit.vue') }
    ] },

    { path: '/admin',
    component: page('admin/index.vue'),
    children: [
      { path: '', redirect: { name: 'admin.home' } },
      { path: 'home', name: 'admin.home', redirect: { name: 'admin.applications.list'}  },
      { path: 'users', name: 'admin.users', component: page('admin/users.vue') },
      { path: 'access_right', name: 'admin.access_right', component: page('admin/access_right.vue') },
      { path: 'university_information', 
        component: page('admin/university_information/index.vue'),
        children: [
          { path: '', redirect: { name: 'admin.university_information.education_levels' } },
          { path: 'education_levels', name: 'admin.university_information.education_levels', component: page('admin/university_information/education_levels.vue') },
          { path: 'faculties', name: 'admin.university_information.faculties', component: page('admin/university_information/faculties.vue') },
          { path: 'specialties', name: 'admin.university_information.specialties', component: page('admin/university_information/specialties.vue') },
          { path: 'specialties_info', name: 'admin.university_information.specialties_info', component: page('admin/university_information/specialties_info.vue') },
          { path: 'additional_marks', name: 'admin.university_information.additional_marks', component: page('admin/university_information/additional_marks.vue') },
          { path: 'use', name: 'admin.university_information.use', component: page('admin/university_information/use.vue') },
          { path: 'identification_documents_name', name: 'admin.university_information.identification_documents_name', component: page('admin/university_information/identification_documents_name.vue') },
          { path: 'document_on_education_name', name: 'admin.university_information.document_on_education_name', component: page('admin/university_information/document_on_education_name.vue') },
          { path: 'stage_of_education', name: 'admin.university_information.stage_of_education', component: page('admin/university_information/stage_of_education.vue') },
        ] },
      { path: 'documents', 
        component: page('admin/documents/index.vue'),
        children: [
          { path: '', redirect: { name: 'admin.documents.home' } },
          { path: 'home', name: 'admin.documents.home', component: page('admin/documents/home.vue') },
          { path: 'categories', name: 'admin.documents.categories', component: page('admin/documents/categories.vue') },
          { path: 'add', name: 'admin.documents.add', component: page('admin/documents/add.vue') },
        ] },
      { path: 'info', 
        component: page('admin/info/index.vue'),
        children: [
          { path: '', redirect: { name: 'admin.info.home' } },
          { path: 'home', name: 'admin.info.home', component: page('admin/info/home.vue') },
          { path: 'add', name: 'admin.info.add', component: page('admin/info/add.vue') },
          { path: 'categories', name: 'admin.info.categories', component: page('admin/info/categories.vue') },
        ] },  
        { path: 'vk_bot', 
        component: page('admin/vk_bot/index.vue'),
        children: [
          { path: '', redirect: { name: 'admin.vk_bot.home' } },
          { path: 'home', name: 'admin.vk_bot.index', component: page('admin/vk_bot/home.vue') },
          { path: 'settings', name: 'admin.vk_bot.settings', component: page('admin/vk_bot/settings.vue') },
          { path: 'chats', name: 'admin.vk_bot.chats', component: page('admin/vk_bot/chats.vue') },
        ] },  
      { path: 'system', name: 'admin.system', component: page('admin/system.vue') }
    ] },
    { path: '/admin/applications', 
      component: page('admin/applications/index.vue'),
      children: [
          { path: '', redirect: { name: 'admin.applications.list' } },
          { path: 'list', name: 'admin.applications.list', component: page('admin/applications/list.vue') },
          { path: 'get/:id', name: 'admin.applications.get', component: page('admin/applications/get.vue') },
      ] 
    },
    { path: '/admin/news', 
        component: page('admin/news/index.vue'),
        children: [
          { path: '', redirect: { name: 'admin.news.home' } },
          { path: 'home', name: 'admin.news.home', component: page('admin/news/home.vue') },
          { path: 'categories', name: 'admin.news.categories', component: page('admin/news/categories.vue') },
          { path: 'get/:id', name: 'admin.news.get', component: page('admin/news/get.vue') },
          { path: 'add', name: 'admin.news.add', component: page('admin/news/add.vue') },
          { path: 'edit/:id', name: 'admin.news.edit', component: page('admin/news/edit.vue') },
        ] 
    },
    { path: '/admin/articles', 
        component: page('admin/articles/index.vue'),
        children: [
          { path: '', redirect: { name: 'admin.articles.home' } },
          { path: 'home', name: 'admin.articles.home', component: page('admin/articles/home.vue') },
          { path: 'categories', name: 'admin.articles.categories', component: page('admin/articles/categories.vue') },
          { path: 'get/:id', name: 'admin.articles.get', component: page('admin/articles/get.vue') },
          { path: 'add', name: 'admin.articles.add', component: page('admin/articles/add.vue') },
          { path: 'edit/:id', name: 'admin.articles.edit', component: page('admin/articles/edit.vue') },
        ] 
    },

  { path: '*', component: page('errors/404.vue') }
]
