<template>
  <div class="module">
    <div class="module-block bg-white rounded mb-4">

      <template v-if="!isNull(documents_categories) && islength(documents_categories) && !error && !loading">
      <div class="bg-white p-3 border-bottom shadow-sm">
        <div class="d-flex flex-row-reverse">
          <select class="rounded-pill py-2 px-3 border tab-link" id="documents_add_category" v-model="category_select">
            <option v-for="(category, index) in documents_categories" :key="category.id" :value="category.id">{{category.name}}</option>
          </select>
        </div>
      </div>
      </template>

      <template v-if="!isNull(documents) && islength(documents) && !error && !loading">

        <div class="d-flex flex-column p-3 rounded-lg mt-2">
          <div class="documents-item p-3 border mb-3 d-flex" v-for="(item, index) in documents" :key="item.id">
            <div class="d-flex flex-grow-1 flex-column title-wrap">
              <h5 class="title">
                {{item.name}}
              </h5>
              <p>
                {{item.description}}
              </p>
            </div>
            <div class="mr-2">
              <button type="button" class="nav-link btn btn-primary w-100 rounded-pill" @click="document_download(item.id)">{{ $t('download') }}</button>
            </div>
            <div>
              <button type="button" class="nav-link btn btn-danger w-100 rounded-pill" @click="delete_(index, item.id)">{{ $t('delete') }}</button>
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

      <div class="module-wrap p-4  bg-white rounded mb-3" v-if=" ((isNull(documents) || !islength(documents)) && (isNull(documents) || !islength(documents))) || error || loading">
        <div class="p-3 text-center" v-if="isNull(documents) || !islength(documents) && !error && !loading">{{ $t('not_info') }}</div>
        <div class="p-3 text-center text-danger" v-if="error ">{{ $t('error_info') }}</div>
        <div class="spinner-grow text-primary p-3" role="status" v-if="loading">
          <span class="sr-only">{{ $t('loading_info') }}</span>
        </div>
      </div>
      
   	</div>
  </div>
</template>

<script>
import axios from 'axios'
import Form from 'vform'
import { mapGetters } from 'vuex'
import i18n from '~/plugins/i18n'
import Swal from 'sweetalert2'
import { variables } from '~/mixins/variables'

export default {

  mixins: [variables],

  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('admin_documents') }
  },

  data() {
    return {
      loading: false,
      documents: null,
      documents_categories: null,
      category_select: null,
      documents_count: 0,
      error: null,
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
    documents: function () {
      this.page_count =  Math.ceil(this.documents_count / this.page_range);
    },
    category_select: function () {
      this.fetchData()
    }
  },
  methods: {
    fetchData(p = 1) {
      this.error = this.documents = null;
      this.news_count = 0;
      this.loading = true;

      axios.get('/api/admin/documents.get', {
        params: {
          page: p,
          category: this.category_select
        }
      })
      .then(response => {
            this.documents = response.data.data;
            this.documents_count = response.data.total;
      }) 
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    fetchCategories(p = 1) {
      this.documents_categories = null;
      this.loading = true;

      axios
        .get('/api/admin/documents.categories.get')
        .then(response => {
            this.documents_categories = response.data;
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
    },
    document_download(id){
      if(!id)
        return false;

      let statement_download = this.$router.resolve({path: '/api/admin/documents.get_file', query: {id: id}});
      window.open(statement_download.href, '_blank');
    },
    delete_ (index, id){
      if(!id || index === null)
        return false;

        Swal.fire({
            title: this.$t('admin_warning2'),
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.$t('swal_delete_btn'),
            cancelButtonText: this.$t('swal_cancel_btn')

        }).then((result) => {

          if (result.value) {

            axios.post('/api/admin/documents.delete', {
              id: id,
              headers: {
                  'Content-Type': 'application/json'
              }
            }).then((response) => {
              Swal.fire({
                  title: this.$t('swal_title_success'),
                  showConfirmButton: false,
                  timer: 1500
              });
              this.$delete(this.documents, index);
            })
            .catch(function (error) {
              console.log(error);
            }); 
          }
        });
    },
  }
}
</script>
