<template>
  <div class="module">
    <div class="mb-3 d-flex justify-content-between">
      <h2 class="text-dark align-middle mb-0">{{$t('statement_my_from')}}</h2>
    </div>
    <div class="module-wrap p-4  bg-white rounded mb-3 p-3 rounded-lg">
      <div class="d-flex p-3 border rounded-lg align-items-center" v-if="!isNull(statement_my) && islength(statement_my) && !error && !loading">
        <div class="mr-auto p-2">
          <router-link :to="{ name: 'statement.my_form' }">
            <h5 class="mb-0">{{statement_my.full}}</h5>
          </router-link>
        </div>
        <div class="p-2" v-if="!isNull(status) && status >= 2">
          <router-link :to="{ name: 'statement.my_form.edit' }">
            {{$t('statement_my_from_edit')}}
          </router-link>
        </div>
        <div class="p-2" v-if="!isNull(status) && status == 1">
          <router-link :to="{ name: 'statement.my_form.files' }" class="btn btn-primary rounded-lg">
            {{$t('statement_my_from_files_add')}}
          </router-link>
        </div>
      </div>
      <div class="d-flex p-3 border rounded-lg align-items-center" v-if="isNull(statement_my) || !islength(statement_my) && !error && !loading">
        <div class="p-2">
          <router-link :to="{ name: 'statement.my_form.complete' }">
            <h5 class="mb-0">{{$t('statement_my_complete')}}</h5>
          </router-link>
        </div>
      </div>
      <div class="p-4" v-if="error || loading">
        <div class="p-3 text-center text-danger" v-if="error ">{{ $t('error_info') }}</div>
        <div class="spinner-grow text-primary p-3" role="status" v-if="loading">
        <span class="sr-only">{{ $t('loading_info') }}</span>
        </div>
      </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
      <h2 class="text-dark align-middle mb-0">{{$t('statement_my_applications')}}</h2>
      <router-link :to="{ name: 'statement.application.creat' }" class="btn btn-primary rounded-lg">
        {{$t('statement_my_applications_add')}}
      </router-link>
    </div>
    <div class="module-wrap p-4  bg-white rounded mb-3 p-3 rounded-lg">
      <template v-if="!isNull(applications_list) && islength(applications_list) && !error && !loading">
        <div class="d-flex p-3 border rounded-lg align-items-center mb-2" v-for="(application, index) in applications_list" :key="application.id">
          <div class="mr-auto p-2">
            <router-link :to="{ name: 'statement.application.my', params: { id: application.id } }">
              <h5 class="mb-0">{{application.indexer}} 
                <span class="badge badge-secondary mx-2">{{$t('application_date_created_at')}} {{application.created_at}}</span> 
                <span class="badge badge-secondary">{{$t('application_date_updated_at')}} {{application.created_at}}</span>
              </h5>
            </router-link>
          </div>
          <div class="p-2">
            <router-link :to="{ name: 'statement.application.my_edit', params: { id: application.id } }">
              {{$t('application_edit')}}
            </router-link>
          </div>
          <div class="p-2">
            <v-button type="outline-danger" nativeType="button" class="btn-sm" v-on:click.native="delete_a(index, application.id, 2)">{{$t('delete')}}</v-button>
          </div>
        </div>
      </template>
      <div class="d-flex p-3 border rounded-lg align-items-center" v-if="isNull(applications_list) || !islength(applications_list) && !error && !loading">
        <div class="p-2">
          <router-link :to="{ name: 'statement.application.creat' }">
            <h5 class="mb-0">{{$t('statement_my_applications_add')}}</h5>
          </router-link>
        </div>
      </div>
      <div class="p-4" v-if="error || loading">
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
      status: '',
      statement_my: '',
      applications_list: '',
      error: null
    }
  },

  created() {
    this.fetchData()
  },

  methods: {
    fetchData() {
      this.error = this.statement_my = this.applications_list = this.status = null
      this.loading = true

      axios
        .get(`/api/statement.my.get`)
        .then(response => {

          if(!this.isNull(response)){


            this.statement_my = !this.isNull(response.data.statement_my) ? response.data.statement_my : null;
            this.applications_list = !this.isNull(response.data.applications_list) ? response.data.applications_list : null;
            this.status = !this.isNull(response.data.status) ? response.data.status.status : null;

          }
        }) 
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    delete_a (index, id){

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
              axios.post('/api/application.delete', {
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
                this.applications_list.splice(index, 1);
              })
            .catch(function (error) {
              console.log(error);
            }); 
          }   
        });
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
