<template>
 <div class="container-fluid">
    <div class="px-3">
      <h2 class="my-4">{{$t('info_news')}}</h2>
      <div class="row">
        <div class="col-sm-12 col-md-10">
          
          <template v-if="!isNull(news) && islength(news) && !error && !loading">

            <div class="d-flex flex-column rounded-lg">
              <div class="news-item p-3 border mb-3 d-flex bg-white" v-for="(item, index) in news" :key="item.id">
                <div class="d-flex flex-column title-wrap">
                  <h5 class="title">
                    <router-link :to="{ name: 'info.news.get', params: { id: item.id } }">
                      {{item.name}}
                    </router-link>
                  </h5>
                  <div class="d-flex flex-lg-row flex-sm-column">
                    <div class="py-2 d-flex mr-2">
                      <span class="name font-weight-bold mr-1">{{ $t('news_created') }}</span>
                      <span class="font-italic">{{item.created_at}}</span>
                    </div>
                    <div class="py-2 d-flex mr-2">
                      <span class="name font-weight-bold mr-1">{{ $t('news_changed') }}</span>
                      <span class="font-italic">{{item.updated_at}}</span>
                    </div>
                    <div class="py-2 d-flex mr-2">
                      <span class="name font-weight-bold mr-1">{{ $t('news_categories') }}</span>
                      <span class="font-italic">{{item.name_categories}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
              <paginate
                :page-count="this.page_count"
                :page-range="this.page_range"
                :margin-pages="2"
                :click-handler="fetchData"
                :prev-text="this.$t('admin_paginate_prev')"
                :next-text="this.$t('admin_paginate_next')"
                :container-class="'pagination'"
                :page-link-class="'page-link'"
                :page-class="'page-item'"
                :prev-class="'page-item'"
                :next-class="'page-item'"
                :prev-link-class="'page-link'"
                :next-link-class="'page-link'"
                >
              </paginate>
            </div>
          </template>

          <div class="module-wrap p-4 bg-white rounded mb-3" v-if=" ((isNull(news) || !islength(news)) && (isNull(news) || !islength(news))) || error || loading">
            <div class="p-3 text-center" v-if="isNull(news) || !islength(news) && !error && !loading">{{ $t('not_info') }}</div>
            <div class="p-3 text-center text-danger" v-if="error ">{{ $t('error_info') }}</div>
            <div class="spinner-grow text-primary p-3" role="status" v-if="loading">
              <span class="sr-only">{{ $t('loading_info') }}</span>
            </div>
          </div>

        </div>
        <div class="col-12 col-md-2">
          <div class="p-3 border rounded-lg bg-white">
            <template v-if="!isNull(news_categories) && islength(news_categories) && !error && !loading">
              <select class="rounded-pill py-2 px-3 border w-100" id="news_add_category" v-model="category_select">
                <option v-for="(category, index) in news_categories" :key="category.id" :value="category.id">{{category.name}}</option>
              </select>
            </template>
            <div class="p-4" v-if=" ((isNull(news_categories) || !islength(news_categories)) && (isNull(news_categories) || !islength(news_categories))) || error || loading">
              <div class="p-3 text-center" v-if="isNull(news_categories) || !islength(news_categories) && !error && !loading">{{ $t('not_info') }}</div>
              <div class="p-3 text-center text-danger" v-if="error ">{{ $t('error_info') }}</div>
              <div class="spinner-grow text-primary p-3" role="status" v-if="loading">
                <span class="sr-only">{{ $t('loading_info') }}</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
 </div>
</template>

<script>
import axios from 'axios'
import i18n from '~/plugins/i18n'
import Swal from 'sweetalert2'
import { variables } from '~/mixins/variables'

export default {
  mixins: [variables],

  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('statement') }
  },

  data() {
    return {
      loading: false,
      error: null,
      news: null,
      news_categories: null,
      category_select: null,
      page_range: 15,
      page_count: 0
    }
  },

  created() {
    this.fetchData()
    this.fetchCategories()
  },
  watch: {
    $route: 'fetchData',
    news: function () {
      this.page_count =  Math.ceil(this.news_count / this.page_range);
    },
    category_select: function () {
      this.fetchData()
    }
  },
  methods: {
    fetchData(p = 1) {
      this.error = this.news = null;
      this.news_count = 0;
      this.loading = true;

      axios.get('/api/news.get', {
        params: {
          page: p,
          category: this.category_select
        }
      })
      .then(response => {
            this.news = response.data.data;
            this.news_count = response.data.total;
      }) 
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    fetchCategories(p = 1) {
      this.news_categories = null;
      this.loading = true;

      axios
        .get('/api/news.categories.get')
        .then(response => {
            this.news_categories = response.data;
        })
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    isNull(data){
      return this.empty(data);
    },
    islength(data){
      return this.isLength(data);
    }
  }
}
</script>
